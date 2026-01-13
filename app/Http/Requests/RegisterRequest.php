<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:12',               // minimum 12 characters
                'regex:/[a-z]/',       // at least one lowercase letter
                'regex:/[A-Z]/',       // at least one uppercase letter
                'regex:/[0-9]/',       // at least one number
                'regex:/[@$!%*?&#]/',  // at least one special character
                'confirmed',           // must match password_confirmation
            ],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'position' => ['required', 'string'],
            'area_assignment' => ['required', 'integer', 'exists:offices,id'],
            'section' => ['required', 'integer', 'exists:sections,id'],
            'terms' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.confirmed' => 'Password and Confirm Password must match.',
        ];
    }
}
