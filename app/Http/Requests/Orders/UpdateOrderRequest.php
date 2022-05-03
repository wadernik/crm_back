<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'label' => 'sometimes|string|max:255|nullable',
            'comment' => 'sometimes|string|max:255|nullable',
            'amount' => 'sometimes|string',
            'accepted_date' => 'sometimes|date_format:Y-m-d',
            'order_date' => 'sometimes|date_format:Y-m-d|after_or_equal:tomorrow',
            'order_time' => 'sometimes|date_format:H:i',
            'manufacturer_id' => 'sometimes|integer',
            'source_id' => 'sometimes|integer',
            'seller_id' => 'sometimes|integer',
            'file_ids' => 'sometimes|array|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('attributes.order.name'),
            'label' => __('attributes.order.label'),
            'comment' => __('attributes.order.comment'),
            'amount' => __('attributes.order.amount'),
            'accepted_date' => __('attributes.order.accepted_date'),
            'order_date' => __('attributes.order.order_date'),
            'order_time' => __('attributes.order.order_time'),
            'manufacturer_id' => __('attributes.order.manufacturer_id'),
            'source_id' => __('attributes.order.source_id'),
            'seller_id' => __('attributes.order.seller_id'),
            'file_ids' => __('attributes.order.file_ids'),
        ];
    }
}
