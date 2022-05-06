<?php

namespace App\ModelModifiers\ModelFilters;

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
     * @param int $id
     */
    public function filterUserId(int $id): void
    {
        $this->builder->where('user_id', $id);
    }

    /**
     * @param int|array $status
     */
    public function filterStatus(int|array $status): void
    {
        if (is_array($status)) {
            $this->builder->whereIn('status', $status);
        } else {
            $this->builder->where('status', $status);
        }
    }

    /**
     *
     */
    public function filterStatusIsNull(): void
    {
        $this->builder->whereNull('status');
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
    public function filterAcceptedDateStart(string $date): void
    {
        $this->builder->where('accepted_date', '>=', $date);
    }

    /**
     * @param string $date
     */
    public function filterAcceptedDateEnd(string $date): void
    {
        $this->builder->where('accepted_date', '<=', $date);
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

    /**
     * @param string $name
     */
    public function filterName(string $name): void
    {
        $this->builder->where('order_details.name', 'like', '%' . $name . '%');
    }
}
