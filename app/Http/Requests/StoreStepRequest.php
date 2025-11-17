<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Optionally restrict based on role or section
        return true;
    }

    public function rules()
    {
        return [
            'step_name' => [
                'required',
                Rule::unique('steps')->where(function ($q) {
                    return $q->where('section_id', Auth::user()->section_id);
                }),
            ],
        ];
    }
}
