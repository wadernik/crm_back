<?php

namespace App\Http\Requests\Sellers;

use Illuminate\Foundation\Http\FormRequest;

class ListSellersRequest extends FormRequest
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
        ];
    }
}
