<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWindowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'step_id' => 'required|exists:steps,id',
        ];
    }

    public function messages(): array
    {
        return [
            'step_id.required' => 'Step ID is required.',
            'step_id.exists' => 'The selected step does not exist.',
        ];
    }
}
