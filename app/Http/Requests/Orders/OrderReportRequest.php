<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use function __;

class OrderReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.order_date_start' => 'required|date_format:Y-m-d',
            'filter.order_date_end' => 'required|date_format:Y-m-d',
        ];
    }

    public function attributes(): array
    {
        return [
            'filter.accepted_date_start' => __('attributes.order.accepted_date'),
            'filter.accepted_date_end' => __('attributes.order.accepted_date'),
        ];
    }
}
