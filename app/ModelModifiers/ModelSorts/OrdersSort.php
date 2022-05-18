<?php

namespace App\ModelModifiers\ModelSorts;

class OrdersSort extends AbstractBaseModelSort
{
    private const ORDERS_TABLE_ALIAS = 'orders';
    private const USERS_TABLE_ALIAS = 'users';

    /**
     * @param string $direction
     */
    public function sortId(string $direction): void
    {
        $this->builder->orderBy(self::ORDERS_TABLE_ALIAS . '.id', $direction);
    }

    /**
     * @param string $direction
     */
    public function sortOrderDate(string $direction): void
    {
        $this->builder->orderBy(self::ORDERS_TABLE_ALIAS . '.order_date', $direction);
    }

    /**
     * TODO: think about it, how to join necessary tables conditionally
     * @param string $direction
     */
    public function sortUserId(string $direction): void
    {
        $this->builder->orderBy(self::USERS_TABLE_ALIAS . '.first_name', $direction);
    }
}
