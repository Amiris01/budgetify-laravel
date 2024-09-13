<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'location' => ['required', 'string'],
            'status' => ['required', 'string'],
            'attachment' => ['nullable'],
            'start_date' => ['required', 'string'],
            'start_time' => ['required', 'string'],
            'end_date' => ['required', 'string'],
            'end_time' => ['required', 'string'],
            'remarks' => ['required', 'string'],
        ];
    }
}
