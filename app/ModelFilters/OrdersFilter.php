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
}
