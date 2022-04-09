<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'label' => 'sometimes|string|max:255|nullable',
            'comment' => 'sometimes|string|max:255|nullable',
            'amount' => 'required|string',
            'accepted_date' => 'required|date_format:Y-m-d',
            'order_date' => 'required|date_format:Y-m-d',
            'order_time' => 'required|date_format:H:i',
            'manufacturer_id' => 'required|integer',
            'source_id' => 'required|integer',
            'seller_id' => 'required|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => __('attributes.user.username'),
            'password' => __('attributes.user.password'),
            'first_name' => __('attributes.user.first_name'),
            'last_name' => __('attributes.user.last_name'),
            'email' => __('attributes.user.email'),
            'birth_date' => __('attributes.user.birth_date'),
            'role_id' => __('attributes.user.role'),
        ];
    }
}
