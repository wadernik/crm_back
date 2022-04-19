<?php

namespace App\Services\Orders;

use App\ModelFilters\OrdersFilter;
use App\Models\OrderDetailDraft;
use App\Models\OrderDraft;

class OrdersDraftsCollectionService extends BaseOrdersCollectionService
{
    public function __construct(OrderDraft $orderDraft, OrderDetailDraft $orderDetailDraft, OrdersFilter $filter)
    {
        $this->modelClass = $orderDraft;
        $this->orderDetailClass = $orderDetailDraft;
        $this->modelFilter = $filter;
    }
}
