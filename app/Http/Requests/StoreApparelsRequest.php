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
        $data = $this->all();
        // dd($data);
        if ($data['apparelType'] == 'transaction') {
            return [
                "trans_type" => ['required', 'string'],
                "category" => ['required', 'integer'],
                "amount" => ['required', 'numeric'],
                "wallet_id" => ['required', 'integer'],
                "trans_date" => ['required', 'string'],
                "description" => ['required', 'string'],
                "budget_id" => ['nullable', 'integer'],
                "type1" => ['required', 'integer'],
                "size1" => ['required', 'string'],
                "color1" => ['required', 'string'],
                "quantity1" => ['required', 'integer'],
                "brand1" => ['required', 'integer'],
                "price1" => ['required', 'numeric'],
                "style1" => ['required', 'integer'],
                "purchase_date1" => ['required', 'string'],
                "remarks1" => ['required', 'string'],
                "attachment" => ['required', 'file'],
            ];
        } else {
            return [
                "type1" => ['required', 'integer'],
                "size1" => ['required', 'string'],
                "color1" => ['required', 'string'],
                "quantity1" => ['required', 'integer'],
                "brand1" => ['required', 'integer'],
                "style1" => ['required', 'integer'],
                "remarks1" => ['required', 'string'],
            ];
        }
    }
}
