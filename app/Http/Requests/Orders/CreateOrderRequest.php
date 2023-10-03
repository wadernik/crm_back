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
            'manufacturer_id' => 'required|integer',
            'source_id' => 'required|integer',
            'seller_id' => 'required|integer',
            'user_id' => 'sometimes|integer',
            'inspector_id' => 'sometimes|integer',
            'phone' => 'required|regex:/^\d{11}$/',
            'accepted_date' => 'required|date_format:Y-m-d',
            'order_date' => 'required|date_format:Y-m-d|after_or_equal:tomorrow',
            'order_time' => 'required|date_format:H:i',
            'number_external' => 'sometimes|string|nullable',
            //
            'items' => 'required|array|min:1',
            'items.*.title_id' => 'sometimes|integer',
            // 'items.*.name' => 'sometimes|string|max:255',
            'items.*.amount' => 'required|string',
            'items.*.label' => 'sometimes|string|max:255|nullable',
            'items.*.comment' => 'sometimes|string|max:255|nullable',
            'items.*.decoration' => 'sometimes|string|max:255|nullable',
            //
            'files' => 'sometimes|array|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'manufacturer_id' => __('attributes.order.manufacturer_id'),
            'source_id' => __('attributes.order.source_id'),
            'seller_id' => __('attributes.order.seller_id'),
            'accepted_date' => __('attributes.order.accepted_date'),
            'order_date' => __('attributes.order.order_date'),
            'order_time' => __('attributes.order.order_time'),
            'items' => __('attributes.order.items'),
            // 'items.*.name' => __('attributes.order.name'),
            'items.*.label' => __('attributes.order.label'),
            'items.*.comment' => __('attributes.order.comment'),
            'items.*.decoration' => __('attributes.order.decoration'),
            'items.*.amount' => __('attributes.order.amount'),
            'items.*.files' => __('attributes.order.files'),
        ];
    }
}