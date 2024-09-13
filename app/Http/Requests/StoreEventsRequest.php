<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventsRequest extends FormRequest
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
            'name1' => ['required', 'string'],
            'location1' => ['required', 'string'],
            'status1' => ['required', 'string'],
            'attachment1' => ['required','file'],
            'start_date1' => ['required', 'string'],
            'start_time1' => ['required', 'string'],
            'end_date1' => ['required', 'string'],
            'end_time1' => ['required', 'string'],
            'remarks1' => ['required', 'string'],
        ];
    }
}
