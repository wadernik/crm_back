<?php

namespace App\Http\Requests\Sellers;

use Illuminate\Foundation\Http\FormRequest;

class CreateSellerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'sometimes|regex:/^\d{11}$/|nullable',
            'email' => 'sometimes|string|email|nullable',
        ];
    }
}
