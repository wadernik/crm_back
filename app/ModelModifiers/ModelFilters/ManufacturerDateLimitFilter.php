<?php

namespace App\ModelModifiers\ModelFilters;

class ManufacturerDateLimitFilter extends AbstractBaseModelFilter
{
    /**
     * @param int $id
     */
    public function filterId(int $id): void
    {
        $this->builder->where($this->getColumnName('id'), $id);
    }

    /**
     * @param int $manufacturerId
     */
    public function filterManufacturerId(int $manufacturerId): void
    {
        $this->builder->where($this->getColumnName('id'), $manufacturerId);
    }

    /**
     * @param string $date
     */
    public function filterDate(string $date): void
    {
        $this->builder->where($this->getColumnName('date'), $date);
    }

    /**
     * @param string $date
     */
    public function filterDateGte(string $date): void
    {
        $this->builder->where($this->getColumnName('date'), '>=', $date);
    }

    /**
     * @param string $date
     */
    public function filterDateLte(string $date): void
    {
        $this->builder->where($this->getColumnName('date'), '<=', $date);
    }

    /**
     * @param int $type
     */
    public function filterLimitType(int $type): void
    {
        $this->builder->where($this->getColumnName('limit_type'), $type);
    }
}
