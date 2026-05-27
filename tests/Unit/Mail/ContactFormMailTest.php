<?php

use App\Mail\ContactFormMail;

test('contact form mail envelope has correct reply-to address', function () {
    $mail = new ContactFormMail('sender@example.com', 'Hello', 'Test message body.');
    $envelope = $mail->envelope();

    $replyTo = $envelope->replyTo;
    expect($replyTo)->toHaveCount(1);
    expect($replyTo[0]->address)->toBe('sender@example.com');
});

test('contact form mail envelope subject is prefixed correctly', function () {
    $mail = new ContactFormMail('sender@example.com', 'Job Inquiry', 'Test message body.');
    $envelope = $mail->envelope();

    expect($envelope->subject)->toBe('Contact Form Submission: Job Inquiry');
});

test('contact form mail content uses the contact email view', function () {
    $mail = new ContactFormMail('sender@example.com', 'Hello', 'Test message body.');
    $content = $mail->content();

    expect($content->view)->toBe('emails.contact');
});

test('contact form mail content passes email subject and message to view', function () {
    $mail = new ContactFormMail('sender@example.com', 'Hello', 'The message body text.');
    $content = $mail->content();

    expect($content->with['email'])->toBe('sender@example.com');
    expect($content->with['subject'])->toBe('Hello');
    expect($content->with['messageContent'])->toBe('The message body text.');
});

test('contact form mail has no attachments', function () {
    $mail = new ContactFormMail('sender@example.com', 'Hello', 'Body.');

    expect($mail->attachments())->toBe([]);
});
