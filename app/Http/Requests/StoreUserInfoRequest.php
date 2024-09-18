<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserInfoRequest extends FormRequest
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
            'user_id' => ['required', 'integer'],
            "first_name" => ['nullable', 'string'],
            "last_name" => ['nullable', 'string'],
            "email" => ['required', 'string'],
            "birth_date" => ['nullable', 'string'],
            "gender" => ['nullable', 'string'],
            "phone_number" => ['nullable', 'string'],
            "address" => ['nullable', 'string'],
            "bio" => ['nullable', 'string'],
            "profile_pic" => ['nullable', 'file'],
        ];
    }
}
