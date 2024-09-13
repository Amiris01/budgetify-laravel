<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApparelsRequest extends FormRequest
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
            'id' => ['required', 'integer'],
            'type' => ['required', 'integer'],
            'size' => ['required', 'string'],
            'color' => ['required', 'string'],
            'quantity' => ['required', 'integer'],
            'brand' => ['required', 'integer'],
            'price' => ['required', 'numeric'],
            'style' => ['required', 'integer'],
            'purchase_date' => ['required', 'string'],
            'remarks' => ['required', 'string'],
        ];
    }
}
