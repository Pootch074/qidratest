<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $value,
        ])->json();

        // Check success
        if (!isset($response['success']) || !$response['success']) {
            $fail('reCAPTCHA verification failed.');
        }

        // Optional: block if score is too low
        if (isset($response['score']) && $response['score'] < 0.5) {
            $fail('Your login attempt looks suspicious. Please try again.');
        }

        // Optional: log the score for review
        Log::info('reCAPTCHA v3 score', [
            'ip' => request()->ip(),
            'score' => $response['score'] ?? null,
            'action' => $response['action'] ?? null,
        ]);
    }
}
