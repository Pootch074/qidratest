<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all authorized users for now
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id'); // Capture the {id} from the route

        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'password' => 'nullable|string|min:6',
            'position' => 'nullable|string',
            'user_type' => 'required|integer|in:0,1,2,3,4',
            'assigned_category' => 'nullable|in:regular,priority',
            'window_id' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already in use by another user.',
        ];
    }
}
