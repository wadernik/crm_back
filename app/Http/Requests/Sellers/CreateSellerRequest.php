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
            'address' => 'sometimes|string|max:255|nullable',
            'working_hours' => 'sometimes|string|max:255|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('attributes.seller.name'),
            'phone' => __('attributes.seller.phone'),
            'email' => __('attributes.seller.email'),
            'address' => __('attributes.seller.address'),
            'working_hours' =>
                __('attributes.seller.working_hours'),
        ];
    }
}
