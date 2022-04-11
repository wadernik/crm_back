<?php

namespace App\ModelFilters;

class FilesFilter extends BaseModelFilter
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
