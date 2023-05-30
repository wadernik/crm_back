<?php

namespace App\Http\Requests\Manufacturers;

use Illuminate\Foundation\Http\FormRequest;

class ListManufacturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.id' => 'sometimes|integer|nullable',
            'filter.ids' => 'sometimes|array|nullable',
            'limit' => 'sometimes',
            'page' => 'sometimes',
            'sort' => 'sometimes|string',
            'order' => 'sometimes|string',
        ];
    }
}