<?php

use App\Mail\ContactFormMail;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

const TURNSTILE_SITEVERIFY_URL = 'challenges.cloudflare.com/turnstile/v0/siteverify';

beforeEach(function () {
    config(['services.turnstile.secret' => 'a-secret-from-cloudflare']);

    // Any call this suite forgot to stub should blow up rather than hit Cloudflare for real.
    Http::preventStrayRequests();
});

/**
 * Stub the siteverify endpoint. Call once per test: `Http::fake()` merges stubs and the
 * first match wins, so a second call cannot override the first.
 */
function turnstileReplies(mixed $body, int $status = 200): void
{
    Http::fake([TURNSTILE_SITEVERIFY_URL => Http::response($body, $status)]);
}

function turnstileAccepts(): void
{
    turnstileReplies(['success' => true]);
}

/**
 * @param  list<string>  $errorCodes
 */
function turnstileRejects(array $errorCodes = []): void
{
    turnstileReplies(['success' => false, 'error-codes' => $errorCodes]);
}

/**
 * A payload the contact endpoint accepts, before any deliberate breakage.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function contactPayload(array $overrides = []): array
{
    return array_merge([
        'email' => 'sender@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message that is long enough.',
        'turnstile_token' => 'a-token-the-widget-issued',
    ], $overrides);
}

test('contact form validates required fields', function () {
    turnstileAccepts();

    $response = $this->postJson('/api/contact', []);

    $response->assertStatus(422);
    $response->assertJsonPath('errors.email', fn ($v) => ! empty($v));
    $response->assertJsonPath('errors.subject', fn ($v) => ! empty($v));
    $response->assertJsonPath('errors.message', fn ($v) => ! empty($v));
    $response->assertJsonPath('errors.turnstile_token.0', 'Please complete the security verification.');

    // A missing token should be caught before it costs a round trip to Cloudflare.
    Http::assertNothingSent();
});

test('contact form validates email format', function () {
    turnstileAccepts();

    $response = $this->postJson('/api/contact', contactPayload(['email' => 'invalid-email']));

    $response->assertStatus(422);
    $response->assertJsonPath('errors.email', fn ($v) => ! empty($v));
});

test('contact form reports field errors without spending the token', function () {
    turnstileAccepts();

    $this->postJson('/api/contact', contactPayload(['subject' => '', 'message' => 'short']))
        ->assertStatus(422)
        ->assertJsonMissingPath('errors.turnstile_token');

    // The token is redeemed in a second validation pass, so a submission that fails the field
    // rules leaves the visitor's challenge unspent and costs Cloudflare nothing.
    Http::assertNothingSent();
});

test('contact form validates message minimum length', function () {
    turnstileAccepts();

    $response = $this->postJson('/api/contact', contactPayload(['message' => 'short']));

    $response->assertStatus(422);
    $response->assertJsonPath('errors.message', fn ($v) => ! empty($v));
});

test('contact form validates subject max length', function () {
    turnstileAccepts();

    $response = $this->postJson('/api/contact', contactPayload(['subject' => str_repeat('a', 256)]));

    $response->assertStatus(422);
    $response->assertJsonPath('errors.subject', fn ($v) => ! empty($v));
});

test('contact form sends email successfully with valid data', function () {
    turnstileAccepts();
    Mail::fake();

    $response = $this->postJson('/api/contact', contactPayload());

    $response->assertOk()
        ->assertJson(['success' => true, 'message' => 'Your message has been sent successfully!']);

    Mail::assertSent(ContactFormMail::class, fn ($mail) => $mail->email === 'sender@example.com' &&
        $mail->subject === 'Test Subject' &&
        $mail->message === 'This is a test message that is long enough.'
    );
});

test('contact form handles email sending failure gracefully', function () {
    turnstileAccepts();
    Mail::shouldReceive('to')->once()->andThrow(new Exception('Mail server error'));

    $response = $this->postJson('/api/contact', contactPayload());

    $response->assertStatus(500)
        ->assertJson(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
});

test('contact form redeems the token at Cloudflare with the configured secret', function () {
    turnstileAccepts();
    Mail::fake();

    $this->postJson('/api/contact', contactPayload())->assertOk();

    Http::assertSent(fn ($request) => $request->url() === 'https://'.TURNSTILE_SITEVERIFY_URL
        && $request['secret'] === 'a-secret-from-cloudflare'
        && $request['response'] === 'a-token-the-widget-issued'
    );
});

test('contact form rejects a token Cloudflare will not vouch for', function () {
    turnstileRejects(['invalid-input-response']);
    Mail::fake();

    $response = $this->postJson('/api/contact', contactPayload());

    $response->assertStatus(422)
        ->assertJsonPath('errors.turnstile_token.0', 'Security verification failed. Please try again.');

    Mail::assertNothingSent();
});

test('contact form tells a visitor that a spent token is spent', function () {
    turnstileRejects(['timeout-or-duplicate']);
    Mail::fake();

    $this->postJson('/api/contact', contactPayload())
        ->assertStatus(422)
        ->assertJsonPath('errors.turnstile_token.0', 'Your security verification expired. Please complete it again.');

    Mail::assertNothingSent();
});

test('contact form reports and hides a rejected secret key', function () {
    turnstileRejects(['invalid-input-secret']);
    Exceptions::fake();
    Mail::fake();

    $this->postJson('/api/contact', contactPayload())
        ->assertStatus(422)
        ->assertJsonPath('errors.turnstile_token.0', 'Security verification is unavailable. Please try again later.');

    Mail::assertNothingSent();
    Exceptions::assertReported(fn (RuntimeException $e) => str_contains($e->getMessage(), 'rejected our secret key'));
});

test('contact form falls back to a generic message for an unrecognised error code', function () {
    turnstileRejects(['bad-request']);
    Mail::fake();

    $this->postJson('/api/contact', contactPayload())
        ->assertStatus(422)
        ->assertJsonPath('errors.turnstile_token.0', 'Security verification failed. Please try again.');

    Mail::assertNothingSent();
});

test('contact form fails closed when no turnstile secret is configured', function () {
    turnstileAccepts();
    Exceptions::fake();
    Mail::fake();
    config(['services.turnstile.secret' => null]);

    $response = $this->postJson('/api/contact', contactPayload());

    $response->assertStatus(422)
        ->assertJsonPath('errors.turnstile_token.0', 'Security verification is unavailable. Please try again later.');

    Mail::assertNothingSent();
    // Cloudflare would have said yes. The missing secret is what rejected this.
    Http::assertNothingSent();
    Exceptions::assertReported(fn (RuntimeException $e) => str_contains($e->getMessage(), 'TURNSTILE_SECRET_KEY is not set'));
});

test('contact form fails closed when Cloudflare cannot be reached', function () {
    Http::fake(fn () => throw new ConnectionException('Connection timed out'));
    Exceptions::fake();
    Mail::fake();

    $response = $this->postJson('/api/contact', contactPayload());

    $response->assertStatus(422)
        ->assertJsonPath('errors.turnstile_token.0', 'Could not reach security verification. Please try again.');

    Mail::assertNothingSent();
    Exceptions::assertReported(ConnectionException::class);
});

test('contact form fails closed when Cloudflare returns an error status', function () {
    turnstileReplies('', 503);
    Exceptions::fake();
    Mail::fake();

    $response = $this->postJson('/api/contact', contactPayload());

    $response->assertStatus(422)
        ->assertJsonPath('errors.turnstile_token.0', 'Could not reach security verification. Please try again.');

    Mail::assertNothingSent();
    Exceptions::assertReported(fn (RuntimeException $e) => str_contains($e->getMessage(), 'returned HTTP 503'));
});
