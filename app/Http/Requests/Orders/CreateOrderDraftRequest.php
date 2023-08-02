<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manufacturer_id' => 'sometimes|integer',
            'source_id' => 'sometimes|integer',
            'seller_id' => 'sometimes|integer',
            'user_id' => 'sometimes|integer',
            'inspector_id' => 'sometimes|integer',
            'phone' => 'sometimes|regex:/^\d{11}$/',
            'accepted_date' => 'sometimes|date_format:Y-m-d',
            'order_date' => 'sometimes|date_format:Y-m-d|after_or_equal:tomorrow',
            'order_time' => 'sometimes|date_format:H:i',
            'number_external' => 'sometimes|string|nullable',
            //
            'items' => 'sometimes|array',
            'items.*.name' => 'sometimes|string|max:255',
            'items.*.amount' => 'sometimes|string',
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
            'items.*.name' => __('attributes.order.name'),
            'items.*.label' => __('attributes.order.label'),
            'items.*.comment' => __('attributes.order.comment'),
            'items.*.decoration' => __('attributes.order.decoration'),
            'items.*.amount' => __('attributes.order.amount'),
            'items.*.files' => __('attributes.order.files'),
        ];
    }
}