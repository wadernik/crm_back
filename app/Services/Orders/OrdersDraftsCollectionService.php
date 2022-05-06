<?php

namespace App\Services\Orders;

use App\ModelModifiers\ModelFilters\OrdersFilter;
use App\ModelModifiers\ModelSorts\OrdersSort;
use App\Models\OrderDraft;

class OrdersDraftsCollectionService extends BaseOrdersCollectionService
{
    public function __construct(OrderDraft $orderDraft, OrdersFilter $filter, OrdersSort $sort)
    {
        $this->modelClass = $orderDraft;
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
