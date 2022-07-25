<?php

namespace App\ModelModifiers\ModelFilters;

class ManufacturersFilter extends AbstractBaseModelFilter
{
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
}
