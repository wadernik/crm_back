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
            'amount' => 'required|string',
            'label' => 'sometimes|string|max:255|nullable',
            'comment' => 'sometimes|string|max:255|nullable',
            'decoration' => 'sometimes|string|max:255|nullable',
            'accepted_date' => 'required|date_format:Y-m-d',
            'order_date' => 'required|date_format:Y-m-d|after_or_equal:tomorrow',
            'order_time' => 'required|date_format:H:i',
            'number_external' => 'sometimes|string|nullable',
            'manufacturer_id' => 'required|integer',
            'source_id' => 'required|integer',
            'seller_id' => 'required|integer',
            'user_id' => 'sometimes|integer',
            'files' => 'sometimes|array|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('attributes.order.name'),
            'label' => __('attributes.order.label'),
            'comment' => __('attributes.order.comment'),
            'decoration' => __('attributes.order.decoration'),
            'amount' => __('attributes.order.amount'),
            'accepted_date' => __('attributes.order.accepted_date'),
            'order_date' => __('attributes.order.order_date'),
            'order_time' => __('attributes.order.order_time'),
            'manufacturer_id' => __('attributes.order.manufacturer_id'),
            'source_id' => __('attributes.order.source_id'),
            'seller_id' => __('attributes.order.seller_id'),
            'files' => __('attributes.order.files'),
        ];
    }
}