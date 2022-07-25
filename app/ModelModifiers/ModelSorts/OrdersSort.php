<?php

namespace App\ModelModifiers\ModelSorts;

class OrdersSort extends AbstractBaseModelSort
{
    private const USERS_TABLE_ALIAS = 'users';

    /**
     * @param string $direction
     */
    public function sortId(string $direction): void
    {
        $this->builder->orderBy($this->getColumnName('id'), $direction);
    }

    /**
     * @param string $direction
     */
    public function sortOrderDate(string $direction): void
    {
        $this->builder->orderBy($this->getColumnName('order_date'), $direction);
    }

    /**
     * It is necessary to join user's table for this sorting to work correctly.
     * @param string $direction
     */
    public function sortUserId(string $direction): void
    {
        $this->builder->orderBy(self::USERS_TABLE_ALIAS . '.first_name', $direction);
    }
}
