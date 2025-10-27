<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ValidateUserQueueRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        // No typical input fields; we validate logged-in user
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user();
            if (! $user->step_id || ! $user->section_id || ! $user->window_id) {
                $validator->errors()->add('user', 'User is not assigned to a step, section, or window.');
            }
        });
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 400)
        );
    }
}
