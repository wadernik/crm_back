<?php

namespace App\Http\Requests\Delivery;

use Illuminate\Foundation\Http\FormRequest;
use function __;

class ListDeliveryByUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
            'filter.delivery_date_start' => __('attributes.order.delivery.delivery_date_start'),
            'filter.delivery_date_end' => __('attributes.order.delivery.delivery_date_end'),
            'filter.delivered' => __('attributes.order.delivery.delivered'),
        ];
    }
}