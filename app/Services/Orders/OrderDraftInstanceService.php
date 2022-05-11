<?php

namespace App\Services\Orders;

use App\Models\OrderDraft;

class OrderDraftInstanceService extends AbstractBaseOrderInstanceService
{
    public function __construct(OrderDraft $orderDraft)
    {
        $this->modelClass = $orderDraft;
        $this->joins = [
            [
                'table' => 'order_details',
                'first' => 'order_details.order_id',
                'operator' => '=',
                'second' => 'orders.id',
            ]
        ];
    }
}
