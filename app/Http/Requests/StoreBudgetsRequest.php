<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetsRequest extends FormRequest
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
            'title1' => ['required', 'string'],
            'category1' => ['required', 'integer'],
            'total_amount1' => ['required', 'numeric'],
            'remarks1' => ['required', 'string'],
            'start_date1' => ['required', 'string'],
            'end_date1' => ['required', 'string'],
        ];
    }
}
