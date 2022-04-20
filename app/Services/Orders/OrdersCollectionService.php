<?php

namespace App\Services\Orders;

use App\ModelFilters\OrdersFilter;
use App\Models\Order;

class OrdersCollectionService extends BaseOrdersCollectionService
{
    public function __construct(Order $order, OrdersFilter $filter)
    {
        $this->modelClass = $order;
        $this->modelFilter = $filter;
    }
}
