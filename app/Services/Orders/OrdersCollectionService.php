<?php

namespace App\Services\Orders;

use App\ModelModifiers\ModelFilters\OrdersFilter;
use App\ModelModifiers\ModelSorts\OrdersSort;
use App\Models\Order;

class OrdersCollectionService extends BaseOrdersCollectionService
{
    public function __construct(Order $order, OrdersFilter $filter, OrdersSort $sort)
    {
        $this->modelClass = $order;
        $this->modelFilter = $filter;
        $this->modelSort = $sort;
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
