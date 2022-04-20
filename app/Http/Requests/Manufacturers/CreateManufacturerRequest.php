<?php

namespace App\Http\Requests\Manufacturers;

use Illuminate\Foundation\Http\FormRequest;

class CreateManufacturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'sometimes|string|max:255|nullable',
            'phone' => 'sometimes|regex:/^\d{11}$/|nullable',
            'email' => 'sometimes|string|email|nullable',
            'limit' => 'sometimes|integer|nullable',
        ];
    }
}
