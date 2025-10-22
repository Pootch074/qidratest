<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Optionally restrict based on role or section
        return true;
    }

    public function rules(): array
    {
        return [
            'step_name' => 'required|string|max:255',
        ];
    }
}
