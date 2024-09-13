<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApparelsRequest extends FormRequest
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
            'type1' => ['required', 'integer'],
            'size1' => ['required', 'string'],
            'color1' => ['required', 'string'],
            'quantity1' => ['required', 'integer'],
            'brand1' => ['required', 'integer'],
            'price1' => ['required', 'numeric'],
            'style1' => ['required', 'integer'],
            'purchase_date1' => ['required', 'string'],
            'remarks1' => ['required', 'string'],
        ];
    }
}
