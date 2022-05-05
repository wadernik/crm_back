<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class ListOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.id' => 'sometimes|integer',
            'filter.ids' => 'sometimes|integer',
            'filter.manufacturer_id' => 'sometimes|integer',
            'filter.source_id' => 'sometimes|integer',
            'filter.seller_id' => 'sometimes|integer',
            'filter.number' => 'sometimes|string',
            'filter.status' => 'sometimes|integer',
            'filter.statuses' => 'sometimes|array',
            'filter.order_date' => 'sometimes|date_format:Y-m-d',
            'filter.order_date_start' => 'sometimes|date_format:Y-m-d',
            'filter.order_date_end' => 'sometimes|date_format:Y-m-d',
            'filter.accepted_date_start' => 'sometimes|date_format:Y-m-d',
            'filter.accepted_date_end' => 'sometimes|date_format:Y-m-d',
            'limit' => 'sometimes',
            'page' => 'sometimes',
            'sort' => 'sometimes|string',
            'order' => 'sometimes|string',
        ];
    }
}
