<?php

use Illuminate\Mail\Transport\SesTransport;
use Illuminate\Support\Facades\Mail;

test('ses mailer resolves the ses transport in the configured region', function () {
    config([
        'services.ses.key' => 'an-access-key-id',
        'services.ses.secret' => 'a-secret-access-key',
        'services.ses.region' => 'us-east-1',
    ]);

    // Production sets MAIL_MAILER=ses; building the transport fails here if aws/aws-sdk-php goes missing.
    $transport = Mail::mailer('ses')->getSymfonyTransport();

    expect($transport)->toBeInstanceOf(SesTransport::class)
        ->and($transport->ses()->getRegion())->toBe('us-east-1');
});
