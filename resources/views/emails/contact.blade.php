<div>
    <h2>New Contact Form Submission from jonrussell.dev</h2>

    <div style="margin: 20px 0;">
        <p><strong>From:</strong> {{ $email }}</p>
        <p><strong>Subject:</strong> {{ $subject }}</p>
    </div>

    <div style="background: #f5f5f5; padding: 20px; border-radius: 5px;">
        <h3>Message:</h3>
        <p style="white-space: pre-line;">{{ $messageContent }}</p>
    </div>

    <div style="margin-top: 20px; font-size: 12px; color: #666;">
        <p>This email was sent from the contact form on {{ config('app.name') }}.</p>
    </div>
</div>
