<?php

namespace App\Http\Requests\Delivery;

use Illuminate\Foundation\Http\FormRequest;
use function __;

class ListDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.courier_id' => 'sometimes|integer|gt:0|nullable',
            'filter.delivery_date_start' => 'sometimes|date_format:Y-m-d',
            'filter.delivery_date_end' => 'sometimes|date_format:Y-m-d',
            'filter.delivered' => 'sometimes|boolean',
            'limit' => 'sometimes',
            'page' => 'sometimes',
            'sort' => 'sometimes|string',
            'order' => 'sometimes|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'filter.courier_id' => __('attributes.order.delivery.courier_id'),
            'filter.delivery_date_start' => __('attributes.order.delivery.delivery_date_start'),
            'filter.delivery_date_end' => __('attributes.order.delivery.delivery_date_end'),
            'filter.delivered' => __('attributes.order.delivery.delivered'),
        ];
    }
}