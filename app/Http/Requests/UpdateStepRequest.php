<?php

namespace App\Http\Requests;

use App\Models\Step;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'step_name' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Allow "None" as many times as needed
                    if ($value === 'None') {
                        return;
                    }

                    $exists = Step::where('section_id', Auth::user()->section_id)
                        ->where('step_name', $value)
                        ->where('id', '!=', $this->route('id')) // exclude current step
                        ->exists();

                    if ($exists) {
                        $fail('This step name already exists in your section.');
                    }
                },
            ],
        ];
    }
}
