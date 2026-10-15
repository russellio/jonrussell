<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Rules\ValidTurnstileToken;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'turnstile_token' => ['required', 'string'],
        ], [
            'turnstile_token.required' => 'Please complete the security verification.',
        ]);

        // Redeemed in a second pass, on purpose. A Turnstile token is single-use, so spending
        // it alongside the field rules would burn the visitor's challenge — and cost a round
        // trip to Cloudflare — just to tell them their subject line is too long.
        $request->validate([
            'turnstile_token' => [new ValidTurnstileToken],
        ]);

        try {
            Mail::to(config('mail.contact_email'))
                ->send(new ContactFormMail(
                    $validated['email'],
                    $validated['subject'],
                    $validated['message']
                ));

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully!',
            ]);
        } catch (Exception $e) {
            Log::error('Contact form error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.',
            ], 500);
        }
    }
}
