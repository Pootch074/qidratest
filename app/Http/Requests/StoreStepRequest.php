<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreStepRequest extends FormRequest
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
                // Allow "None" always
                if ($value === "None") {
                    return;
                }

                // Normal duplicate check for other names
                $exists = \App\Models\Step::where('section_id', Auth::user()->section_id)
                    ->where('step_name', $value)
                    ->exists();

                if ($exists) {
                    $fail("This step name already exists in your section.");
                }
            }
        ],
    ];
}

}
