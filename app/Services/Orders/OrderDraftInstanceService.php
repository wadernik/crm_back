<?php

namespace App\Services\Orders;

use App\Models\OrderDraft;

class OrderDraftInstanceService extends BaseOrderInstanceService
{
    public function __construct(OrderDraft $orderDraft)
    {
        $this->modelClass = $orderDraft;
    }
}
