<?php

namespace App\ModelFilters;

class ManufacturersFilter extends BaseModelFilter
{
    public function filterId(int $id): void
    {
        $this->builder->where('id', $id);
    }

    public function filterIds(array $ids): void
    {
        $this->builder->whereIn('id', $ids);
    }
}
