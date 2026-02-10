<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use function __;

class AcceptedOrdersReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.date_start' => 'required|date_format:Y-m-d',
            'filter.date_end' => 'required|date_format:Y-m-d',
        ];
    }

    public function attributes(): array
    {
        return [
            'filter.date_start' => __('attributes.order.accepted_date'),
            'filter.date_end' => __('attributes.order.accepted_date'),
        ];
    }
}
