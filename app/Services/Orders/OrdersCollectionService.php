<?php

namespace App\Services\Orders;

use App\ModelFilters\OrdersFilter;
use App\Models\Order;
use App\Models\OrderDetail;

class OrdersCollectionService extends BaseOrdersCollectionService
{
    public function __construct(Order $order, OrderDetail $orderDetail, OrdersFilter $filter)
    {
        $this->modelClass = $order;
        $this->orderDetailClass = $orderDetail;
        $this->modelFilter = $filter;
    }
}
