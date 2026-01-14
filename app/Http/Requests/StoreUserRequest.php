<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'position' => 'nullable|string',
            'user_type' => 'required|integer|in:0,1,2,3,4',
            'assigned_category' => 'nullable|in:regular,priority',
            'window_id' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'user_type.in' => 'User type must be one of: 0, 1, 2, 3, or 4.',
        ];
    }
}
