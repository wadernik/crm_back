<?php

namespace App\Services\Orders;

use App\Models\OrderDetailDraft;
use App\Models\OrderDraft;

class OrderDraftInstanceService extends BaseOrderInstanceService
{
    public function __construct(OrderDraft $orderDraft, OrderDetailDraft $orderDetailDraft)
    {
        $this->modelClass = $orderDraft;
        $this->orderDetailClass = $orderDetailDraft;
    }
}
