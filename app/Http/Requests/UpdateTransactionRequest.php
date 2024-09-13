<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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

        if ($data['trans_type'] == 'Income') {
            return [
                "id" => ['required', 'integer'],
                "trans_type" => ['required', 'string'],
                "event_id_income" => ['nullable', 'integer'],
                "wallet_id_income" => ['required', 'integer'],
                "amount_income" => ['required', 'numeric'],
                "category_income" => ['required', 'integer'],
                "description_income" => ['required', 'string'],
                "trans_date_income" => ['required', 'string'],
                'attachment_income' => ['nullable', 'file'],
            ];
        } else {
            return [
                "id_expense" => ['required', 'integer'],
                "trans_type" => ['required', 'string'],
                "event_expense" => ['nullable', 'integer'],
                "category_expense" => ['required', 'integer'],
                "amount_expense" => ['required', 'numeric'],
                "wallet_expense" => ['required', 'integer'],
                "desc_expense" => ['required', 'string'],
                "trans_date_expense" => ['required', 'string'],
                "attachment_expense" => ['nullable', 'file'],
                "allocate_budget_update" => ['nullable', 'string'],
                "update_budget" => ['nullable', 'integer'],
            ];
        }
    }
}
