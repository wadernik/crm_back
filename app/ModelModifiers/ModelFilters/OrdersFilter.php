<?php

namespace App\ModelModifiers\ModelFilters;

class OrdersFilter extends AbstractBaseModelFilter
{
    private const ORDERS_TABLE_ALIAS = 'orders';
    private const ORDER_DETAILS_TABLE_ALIAS = 'order_details';

    /**
     * @param int $id
     */
    public function filterId(int $id): void
    {
        $this->builder->where(self::ORDERS_TABLE_ALIAS . '.id', $id);
    }

    /**
     * @param array $ids
     */
    public function filterIds(array $ids): void
    {
        $this->builder->whereIn(self::ORDERS_TABLE_ALIAS . '.id', $ids);
    }

    /**
     * @param int $id
     */
    public function filterManufacturerId(int $id): void
    {
        $this->builder->where(self::ORDERS_TABLE_ALIAS . '.manufacturer_id', $id);
    }

    /**
     * @param int $id
     */
    public function filterUserId(int $id): void
    {
        $this->builder->where(self::ORDERS_TABLE_ALIAS . '.user_id', $id);
    }

    /**
     * @param array $ids
     */
    public function filterUserIds(array $ids): void
    {
        $this->builder->whereIn(self::ORDERS_TABLE_ALIAS . '.user_id', $ids);
    }

    /**
     * @param int|array $status
     */
    public function filterStatus(int|array $status): void
    {
        if (is_array($status)) {
            $this->builder->whereIn(self::ORDERS_TABLE_ALIAS . '.status', $status);
        } else {
            $this->builder->where(self::ORDERS_TABLE_ALIAS . '.status', $status);
        }
    }

    /**
     * @param string $date
     */
    public function filterOrderDate(string $date): void
    {
        $this->builder->where(self::ORDERS_TABLE_ALIAS . '.order_date', $date);
    }

    /**
     * @param string $date
     */
    public function filterAcceptedDateStart(string $date): void
    {
        $this->builder->where(self::ORDERS_TABLE_ALIAS . '.accepted_date', '>=', $date);
    }

    /**
     * @param string $date
     */
    public function filterAcceptedDateEnd(string $date): void
    {
        $this->builder->where(self::ORDERS_TABLE_ALIAS . '.accepted_date', '<=', $date);
    }

    /**
     * @param string $date
     */
    public function filterOrderDateStart(string $date): void
    {
        $this->builder->where(self::ORDERS_TABLE_ALIAS . '.order_date', '>=', $date);
    }

    /**
     * @param string $date
     */
    public function filterOrderDateEnd(string $date): void
    {
        $this->builder->where(self::ORDERS_TABLE_ALIAS . '.order_date', '<=', $date);
    }

    /**
     * @param string $date
     */
    public function filterCreatedAtStart(string $date): void
    {
        $this->builder->where(self::ORDERS_TABLE_ALIAS . '.created_at', '>=', $date);
    }

    /**
     * @param string $date
     */
    public function filterCreatedAtEnd(string $date): void
    {
        $this->builder->where(self::ORDERS_TABLE_ALIAS . '.created_at', '<=', $date);
    }

    /**
     * @param string $name
     */
    public function filterName(string $name): void
    {
        $this->builder->where(self::ORDER_DETAILS_TABLE_ALIAS . '.name', 'like', '%' . $name . '%');
    }

    /**
     * @param bool $onlyTrashed
     */
    protected function filterOnlyTrashed(bool $onlyTrashed = true): void
    {
        if ($onlyTrashed) {
            $this->builder->onlyTrashed();
        }
    }
}
