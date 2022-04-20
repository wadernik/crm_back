<?php

namespace App\Services\Orders;

use App\ModelFilters\OrdersFilter;
use App\Models\OrderDraft;

class OrdersDraftsCollectionService extends BaseOrdersCollectionService
{
    public function __construct(OrderDraft $orderDraft, OrdersFilter $filter)
    {
        $this->modelClass = $orderDraft;
        $this->modelFilter = $filter;
    }
}
