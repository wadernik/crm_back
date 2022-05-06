<?php

namespace App\ModelModifiers\ModelSorts;

class OrdersSort extends BaseModelSort
{
    /**
     * @param string $direction
     */
    public function sortId(string $direction): void
    {
        $this->builder->orderBy('orders.id', $direction);
    }
}
