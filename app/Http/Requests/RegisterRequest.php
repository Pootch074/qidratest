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
            'office_id' => ['required', 'integer', 'exists:offices,id'],
            'section_id' => ['required', 'integer', 'exists:sections,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,}$/'],
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
            'password_confirmation' => ['required', 'string', 'min:12'],
            'position' => ['required', 'string'],
            'terms' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please enter your first name.',
            'last_name.required' => 'Please enter your last name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password must be at least 12 characters long.',
            'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.confirmed' => 'Password and Confirm Password must match.',
            'password_confirmation.required' => 'Please confirm your password.',
        ];
    }
}
