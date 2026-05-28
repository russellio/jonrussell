<?php

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

test('contact form validates required fields', function () {
    $response = $this->postJson('/api/contact', []);

    $response->assertStatus(422);
    $response->assertJsonPath('errors.email', fn ($v) => ! empty($v));
    $response->assertJsonPath('errors.subject', fn ($v) => ! empty($v));
    $response->assertJsonPath('errors.message', fn ($v) => ! empty($v));
});

test('contact form validates email format', function () {
    $response = $this->postJson('/api/contact', [
        'email' => 'invalid-email',
        'subject' => 'Test Subject',
        'message' => 'This is a test message that is long enough.',
    ]);

    $response->assertStatus(422);
    $response->assertJsonPath('errors.email', fn ($v) => ! empty($v));
});

test('contact form validates message minimum length', function () {
    $response = $this->postJson('/api/contact', [
        'email' => 'test@example.com',
        'subject' => 'Test Subject',
        'message' => 'short',
    ]);

    $response->assertStatus(422);
    $response->assertJsonPath('errors.message', fn ($v) => ! empty($v));
});

test('contact form validates subject max length', function () {
    $response = $this->postJson('/api/contact', [
        'email' => 'test@example.com',
        'subject' => str_repeat('a', 256),
        'message' => 'This is a test message that is long enough.',
    ]);

    $response->assertStatus(422);
    $response->assertJsonPath('errors.subject', fn ($v) => ! empty($v));
});

test('contact form sends email successfully with valid data', function () {
    Mail::fake();

    $response = $this->postJson('/api/contact', [
        'email' => 'sender@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message that is long enough.',
    ]);

    $response->assertOk()
        ->assertJson(['success' => true, 'message' => 'Your message has been sent successfully!']);

    Mail::assertSent(ContactFormMail::class, fn ($mail) =>
        $mail->email === 'sender@example.com' &&
        $mail->subject === 'Test Subject' &&
        $mail->message === 'This is a test message that is long enough.'
    );
});

test('contact form handles email sending failure gracefully', function () {
    Mail::shouldReceive('to')->once()->andThrow(new Exception('Mail server error'));

    $response = $this->postJson('/api/contact', [
        'email' => 'sender@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message that is long enough.',
    ]);

    $response->assertStatus(500)
        ->assertJson(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
});
