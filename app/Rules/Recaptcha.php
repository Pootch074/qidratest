<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $value,
            ]
        )->json();

        if (!($response['success'] ?? false)) {
            $fail('reCAPTCHA validation failed.');
        }

        if (($response['score'] ?? 0) < 0.9) {
            $fail('reCAPTCHA score too low. Try again.');
        }
    }
}
