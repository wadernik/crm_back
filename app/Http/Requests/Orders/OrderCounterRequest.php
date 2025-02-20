<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use function __;

class OrderCounterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.date_start' => 'sometimes|date_format:Y-m-d',
            'filter.date_end' => 'sometimes|date_format:Y-m-d',
        ];
    }

    public function attributes(): array
    {
        return [
            'filter.date_start' => __('attributes.order.date_start'),
            'filter.date_end' => __('attributes.order.date_end'),
        ];
    }
}