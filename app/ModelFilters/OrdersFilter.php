<?php

namespace App\ModelFilters;

class OrdersFilter extends BaseModelFilter
{
    /**
     * @param int $id
     */
    public function filterId(int $id): void
    {
        $this->builder->where('id', $id);
    }

    /**
     * @param array $ids
     */
    public function filterIds(array $ids): void
    {
        $this->builder->whereIn('id', $ids);
    }

    /**
     * @param int $id
     */
    public function filterManufacturerId(int $id): void
    {
        $this->builder->where('manufacturer_id', $id);
    }

    /**
     * @param string $date
     */
    public function filterOrderDate(string $date): void
    {
        $this->builder->where('order_date', $date);
    }

    /**
     * @param string $date
     */
    public function filterOrderDateStart(string $date): void
    {
        $this->builder->where('order_date', '>=', $date);
    }

    /**
     * @param string $date
     */
    public function filterOrderDateEnd(string $date): void
    {
        $this->builder->where('order_date', '<=', $date);
    }
}
