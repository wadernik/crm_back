<?php

namespace App\Http\Requests\Orders;

class UpdateOrderDraftRequest extends CreateOrderDraftRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), ['items.*.id' => 'sometimes|integer|min:1']);
    }
}