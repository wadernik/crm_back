<?php

namespace App\Http\Requests\ManufacturersDateLimits;

class UpdateManufacturerDateLimitRequest extends CreateManufacturerDateLimitRequest
{
    public function rules(): array
    {
        return [
            'manufacturer_id' => 'sometimes|integer',
            'date' => 'sometimes|date_format:Y-m-d',
            'limit_type' => 'sometimes|integer',
        ];
    }
}