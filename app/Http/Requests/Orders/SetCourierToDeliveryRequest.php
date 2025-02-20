<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use function __;

class SetCourierToDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'courier_id' => 'sometimes|integer|gt:0',
        ];
    }

    public function attributes(): array
    {
        return [
            'courier_id' => __('attributes.order.delivery.courier_id'),
        ];
    }
}