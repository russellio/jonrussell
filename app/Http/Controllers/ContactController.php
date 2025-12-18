<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Log;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            Mail::to('info@jonrussell.dev')
                ->send(new ContactFormMail(
                    $request->email,
                    $request->subject,
                    $request->message
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
