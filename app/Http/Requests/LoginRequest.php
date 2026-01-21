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

    // Enable recaptcha
    // public function rules(): array
    // {
    //     return [
    //         'email' => ['required', 'email'],
    //         'password' => ['required', 'string'],
    //         'recaptcha_token' => ['required', new Recaptcha()],
    //     ];
    // }

    // Disable recaptcha
    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];

        // Only validate recaptcha if enabled
        // if (env('RECAPTCHA_ENABLED', false)) {
        //     $rules['recaptcha_token'] = ['required', new Recaptcha()];
        // }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ];
    }
}
