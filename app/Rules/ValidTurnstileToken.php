<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Translation\PotentiallyTranslatedString;
use RuntimeException;

/**
 * Trades a Cloudflare Turnstile token in at the siteverify endpoint.
 *
 * The widget alone protects nothing — anything can POST the endpoint directly and never
 * render it. The signed token is the only part Cloudflare can vouch for, and it only counts
 * once it has been redeemed here.
 *
 * This fails closed. A missing secret or an unreachable Cloudflare rejects the submission
 * and reports, rather than waving it through: a silent fail-open is indistinguishable from
 * having no CAPTCHA at all, which is the state this rule exists to end.
 */
class ValidTurnstileToken implements ValidationRule
{
    private const ENDPOINT = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.turnstile.secret');

        if (! is_string($secret) || $secret === '') {
            report(new RuntimeException('TURNSTILE_SECRET_KEY is not set, so the contact form cannot verify anyone.'));
            $fail('Security verification is unavailable. Please try again later.');

            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Please complete the security verification.');

            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post(self::ENDPOINT, ['secret' => $secret, 'response' => $value]);
        } catch (ConnectionException $e) {
            report($e);
            $fail('Could not reach security verification. Please try again.');

            return;
        }

        if ($response->failed()) {
            report(new RuntimeException("Turnstile siteverify returned HTTP {$response->status()}."));
            $fail('Could not reach security verification. Please try again.');

            return;
        }

        if ($response->json('success') === true) {
            return;
        }

        $codes = array_values(array_filter((array) $response->json('error-codes', []), 'is_string'));

        Log::warning('Turnstile rejected a contact form submission.', ['error_codes' => $codes]);

        $fail($this->messageFor($codes));
    }

    /**
     * Turn Cloudflare's error codes into something worth showing a visitor.
     *
     * @param  list<string>  $codes
     */
    private function messageFor(array $codes): string
    {
        // Our own misconfiguration, not the visitor's problem. Worth a Sentry event, since
        // nobody can use the contact form until it is fixed.
        if (array_intersect($codes, ['invalid-input-secret', 'missing-input-secret'])) {
            report(new RuntimeException('Turnstile rejected our secret key: '.implode(', ', $codes).'.'));

            return 'Security verification is unavailable. Please try again later.';
        }

        // A token is single-use and expires after five minutes, so this is the ordinary
        // failure for a real person who left the form open. Don't imply they look like a bot.
        if (array_intersect($codes, ['timeout-or-duplicate', 'invalid-input-response'])) {
            return 'Your security verification expired. Please complete it again.';
        }

        return 'Security verification failed. Please try again.';
    }
}
