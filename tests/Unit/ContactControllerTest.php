<?php

use App\Http\Controllers\ContactController;
use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

test('contact form validates required fields', function () {
    $controller = new ContactController;
    $request = Request::create('/api/contact', 'POST', []);

    $response = $controller->store($request);
    $json = json_decode($response->getContent(), true);

    expect($response->status())->toBe(422);
    expect($json)->toHaveKey('success', false);
    expect($json)->toHaveKey('errors');
    expect($json['errors'])->toHaveKey('email');
    expect($json['errors'])->toHaveKey('subject');
    expect($json['errors'])->toHaveKey('message');
});

test('contact form validates email format', function () {
    $controller = new ContactController;
    $request = Request::create('/api/contact', 'POST', [
        'email' => 'invalid-email',
        'subject' => 'Test Subject',
        'message' => 'This is a test message that is long enough.',
    ]);

    $response = $controller->store($request);
    $json = json_decode($response->getContent(), true);

    expect($response->status())->toBe(422);
    expect($json)->toHaveKey('success', false);
    expect($json['errors'])->toHaveKey('email');
});

test('contact form validates message minimum length', function () {
    $controller = new ContactController;
    $request = Request::create('/api/contact', 'POST', [
        'email' => 'test@example.com',
        'subject' => 'Test Subject',
        'message' => 'short',
    ]);

    $response = $controller->store($request);
    $json = json_decode($response->getContent(), true);

    expect($response->status())->toBe(422);
    expect($json)->toHaveKey('success', false);
    expect($json['errors'])->toHaveKey('message');
});

test('contact form validates subject max length', function () {
    $controller = new ContactController;
    $request = Request::create('/api/contact', 'POST', [
        'email' => 'test@example.com',
        'subject' => str_repeat('a', 256), // Exceeds max:255
        'message' => 'This is a test message that is long enough.',
    ]);

    $response = $controller->store($request);
    $json = json_decode($response->getContent(), true);

    expect($response->status())->toBe(422);
    expect($json)->toHaveKey('success', false);
    expect($json['errors'])->toHaveKey('subject');
});

test('contact form sends email successfully with valid data', function () {
    Mail::fake();

    $controller = new ContactController;
    $request = Request::create('/api/contact', 'POST', [
        'email' => 'test@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message that is long enough.',
    ]);

    $response = $controller->store($request);
    $json = json_decode($response->getContent(), true);

    expect($response->status())->toBe(200);
    expect($json)->toHaveKey('success', true);
    expect($json['success'])->toBeTrue();
    expect($json['message'])->toBe('Your message has been sent successfully!');

    Mail::assertSent(ContactFormMail::class, function ($mail) use ($request) {
        return $mail->email === $request->email &&
               $mail->subject === $request->subject &&
               $mail->message === $request->message;
    });

    Mail::assertSent(ContactFormMail::class, function ($mail) {
        return $mail->hasTo('info@jonrussell.dev');
    });
});

test('contact form handles email sending failure gracefully', function () {
    // Use Mail spy to capture calls and throw exception
    $mockMailer = Mockery::mock();
    $mockMailer->shouldReceive('send')
        ->once()
        ->andThrow(new \Exception('Mail server error'));

    Mail::shouldReceive('to')
        ->once()
        ->with('info@jonrussell.dev')
        ->andReturn($mockMailer);

    Log::shouldReceive('error')->once();

    $controller = new ContactController;
    $request = Request::create('/api/contact', 'POST', [
        'email' => 'test@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message that is long enough.',
    ]);

    $response = $controller->store($request);
    $json = json_decode($response->getContent(), true);

    expect($response->status())->toBe(500);
    expect($json)->toHaveKey('success', false);
    expect($json['success'])->toBeFalse();
    expect($json['message'])->toBe('Failed to send message. Please try again later.');
});
