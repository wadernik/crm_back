<?php

namespace App\Http\Requests\ManufacturersDateLimits;

use Illuminate\Foundation\Http\FormRequest;

class CreateManufacturerDateLimitsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manufacturer_id' => 'required|integer',
            'date' => 'required_without:dates|date_format:Y-m-d',
            'dates.*' => 'required_without:date|date_format:Y-m-d',
            'limit_type' => 'required|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'manufacturer_id' => __('attributes.manufacturer_date_limit.manufacturer_id'),
            'date' => __('attributes.manufacturer_date_limit.date'),
            'dates' => __('attributes.manufacturer_date_limit.dates'),
            'limit_type' => __('attributes.manufacturer_date_limit.limit_type'),
        ];
    }
}
