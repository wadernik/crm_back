<?php

namespace App\Http\Requests\OrderProduct;

use Illuminate\Foundation\Http\FormRequest;
use function __;

class UpdateOrderProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:1024',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('attributes.order.product.name'),
        ];
    }
}
