<?php

namespace App\ModelModifiers\ModelFilters;

class OrdersFilter extends AbstractBaseModelFilter
{
    private const ORDER_DETAILS_TABLE_ALIAS = 'order_details';

    /**
     * @param int $id
     */
    public function filterId(int $id): void
    {
        $this->builder->where($this->getColumnName('id'), $id);
    }

    /**
     * @param array $ids
     */
    public function filterIds(array $ids): void
    {
        $this->builder->whereIn($this->getColumnName('id'), $ids);
    }

    /**
     * @param int $id
     */
    public function filterManufacturerId(int $manufacturerId): void
    {
        $this->builder->where($this->getColumnName('manufacturer_id'), $manufacturerId);
    }

    /**
     * @param int $id
     */
    public function filterSourceId(int $sourceId): void
    {
        $this->builder->where($this->getColumnName('source_id'), $sourceId);
    }

    public function filterSellerId(int $sellerId): void
    {
        $this->builder->where($this->getColumnName('seller_id'), $sellerId);
    }

    /**
     * @param int $id
     */
    public function filterUserId(int $userId): void
    {
        $this->builder->where($this->getColumnName('user_id'), $userId);
    }

    /**
     * @param array $ids
     */
    public function filterUserIds(array $userIds): void
    {
        $this->builder->whereIn($this->getColumnName('user_id'), $userIds);
    }

    /**
     * @param int|array $status
     */
    public function filterStatus(int|array $status): void
    {
        if (is_array($status)) {
            $this->builder->whereIn($this->getColumnName('status'), $status);
        } else {
            $this->builder->where($this->getColumnName('status'), $status);
        }
    }

    /**
     * @param string $date
     */
    public function filterOrderDate(string $date): void
    {
        $this->builder->where($this->getColumnName('order_date'), $date);
    }

    /**
     * @param string $date
     */
    public function filterAcceptedDateStart(string $date): void
    {
        $this->builder->where($this->getColumnName('accepted_date'), '>=', $date);
    }

    /**
     * @param string $date
     */
    public function filterAcceptedDateEnd(string $date): void
    {
        $this->builder->where($this->getColumnName('accepted_date'), '<=', $date);
    }

    /**
     * @param string $date
     */
    public function filterOrderDateStart(string $date): void
    {
        $this->builder->where($this->getColumnName('order_date'), '>=', $date);
    }

    /**
     * @param string $date
     */
    public function filterOrderDateEnd(string $date): void
    {
        $this->builder->where($this->getColumnName('order_date'), '<=', $date);
    }

    /**
     * @param string $date
     */
    public function filterCreatedAtStart(string $date): void
    {
        $this->builder->where($this->getColumnName('created_at'), '>=', $date);
    }

    /**
     * @param string $date
     */
    public function filterCreatedAtEnd(string $date): void
    {
        $this->builder->where($this->getColumnName('created_at'), '<=', $date);
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
