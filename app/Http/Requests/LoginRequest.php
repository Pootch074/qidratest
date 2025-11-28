<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Recaptcha;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'recaptcha_token' => ['required', new Recaptcha()],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Enter a valid email address.',
            'password.required' => 'Please enter your password.',
            'recaptcha_token.required' => 'reCAPTCHA verification failed.',
        ];
    }
}
