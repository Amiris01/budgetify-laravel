<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWalletsRequest extends FormRequest
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
            'wallet_type1' => ['required', 'integer'],
            'amount1' => ['required' , 'numeric'],
            'fin_institute1' => ['required', 'integer'],
            'description1' => ['required', 'string']
        ];
    }
}
