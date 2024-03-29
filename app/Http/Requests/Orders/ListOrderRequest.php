<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use function __;

class ListOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.id' => 'sometimes|nullable',
            'filter.manufacturer_id' => 'sometimes|integer',
            'filter.source_id' => 'sometimes|integer',
            'filter.seller_id' => 'sometimes|integer',
            'filter.user_id' => 'sometimes|integer',
            'filter.number' => 'sometimes|string',
            'filter.status' => 'sometimes',
            'filter.order_date' => 'sometimes|date_format:Y-m-d',
            'filter.order_date_start' => 'sometimes|date_format:Y-m-d',
            'filter.order_date_end' => 'sometimes|date_format:Y-m-d',
            'filter.accepted_date' => 'sometimes|date_format:Y-m-d',
            'filter.accepted_date_start' => 'sometimes|date_format:Y-m-d',
            'filter.accepted_date_end' => 'sometimes|date_format:Y-m-d',
            'filter.phone' => 'sometimes|regex:/^\d{11}$/',
            'limit' => 'sometimes',
            'page' => 'sometimes',
            'sort' => 'sometimes|string',
            'order' => 'sometimes|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'filter.manufacturer_id' => __('attributes.order.manufacturer_id'),
            'filter.source_id' => __('attributes.order.source_id'),
            'filter.seller_id' => __('attributes.order.seller_id'),
            'filter.accepted_date' => __('attributes.order.accepted_date'),
            'filter.accepted_date_start' => __('attributes.order.accepted_date'),
            'filter.accepted_date_end' => __('attributes.order.accepted_date'),
            'filter.order_date' => __('attributes.order.order_date'),
            'filter.order_time' => __('attributes.order.order_time'),
            'filter.phone' => __('attributes.order.phone'),
        ];
    }
}