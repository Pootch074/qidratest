<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all authorized users for now
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$this->id}",
            'password' => 'nullable|string|min:8',
            'user_type' => 'required|integer',
            'position' => 'nullable|string|max:255',
            'assigned_category' => 'nullable|string|max:255',
            'window_id' => 'nullable|integer|exists:windows,id',
            'status' => 'nullable|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already in use by another user.',
        ];
    }
}
