<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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

        if (isset($data['trans_type']) && $data['trans_type'] === 'Income') {
            return [
                'trans_type' => ['required', 'string'],
                'table_ref1' => ['nullable'],
                'event_id1' => ['nullable', 'integer'],
                'wallet_id1' => ['required', 'integer'],
                'amount1' => ['required', 'numeric'],
                'category1' => ['required', 'integer'],
                'description1' => ['required', 'string'],
                'trans_date1' => ['required', 'string'],
                'attachment1' => ['nullable', 'file'],
            ];
        } else {
            return [
                'trans_type' => ['required', 'string'],
                'table_ref' => ['nullable'],
                'event_id' => ['nullable', 'integer'],
                'wallet_id' => ['required', 'integer'],
                'amount' => ['required', 'numeric'],
                'category' => ['required', 'integer'],
                'description' => ['required', 'string'],
                'trans_date' => ['required', 'string'],
                'attachment' => ['nullable', 'file'],
                'budget_id' => ['nullable', 'integer'],
            ];
        }
    }
}
