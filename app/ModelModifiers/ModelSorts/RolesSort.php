<?php

namespace App\ModelModifiers\ModelSorts;

class RolesSort extends AbstractBaseModelSort
{
    /**
     * @param string $direction
     */
    public function sortId(string $direction): void
    {
        $this->builder->orderBy($this->getColumnName('id'), $direction);
    }

    /**
     * @param string $direction
     */
    public function sortName(string $direction): void
    {
        $this->builder->orderBy($this->getColumnName('name'), $direction);
    }
}
