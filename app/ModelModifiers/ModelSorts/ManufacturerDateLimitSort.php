<?php

namespace App\ModelModifiers\ModelSorts;

class ManufacturerDateLimitSort extends AbstractBaseModelSort
{
    /**
     * @param string $direction
     */
    public function sortId(string $direction): void
    {
        $this->builder->orderBy($this->getColumnName('id'), $direction);
    }
}