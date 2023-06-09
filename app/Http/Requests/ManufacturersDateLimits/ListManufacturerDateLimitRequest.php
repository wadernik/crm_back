<?php

namespace App\Http\Requests\ManufacturersDateLimits;

use Illuminate\Foundation\Http\FormRequest;

class ListManufacturerDateLimitRequest extends FormRequest
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
            'filter.date' => 'sometimes|date_format:Y-m-d',
            'filter.date_gte' => 'sometimes|date_format:Y-m-d',
            'filter.date_lte' => 'sometimes|date_format:Y-m-d',
            'limit' => 'sometimes',
            'page' => 'sometimes',
            'sort' => 'sometimes|string',
            'order' => 'sometimes|string',
        ];
    }
}