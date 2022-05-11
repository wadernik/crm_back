<?php

namespace App\ModelModifiers\ModelFilters;

class RolesFilter extends AbstractBaseModelFilter
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
