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
            'filter.order_date' => 'sometimes|string',
            'filter.order_date_start' => 'sometimes|string',
            'filter.order_date_end' => 'sometimes|string',
        ];
    }
}