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
            'date' => 'required|date_format:Y-m-d',
            'limit_type' => 'required|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'manufacturer_id' => __('attributes.manufacturer_date_limit.manufacturer_id'),
            'date' => __('attributes.manufacturer_date_limit.date'),
            'limit_type' => __('attributes.manufacturer_date_limit.limit_type'),
        ];
    }
}
