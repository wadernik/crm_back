<?php

namespace App\ModelModifiers\ModelSorts;

class PermissionsSort extends AbstractBaseModelSort
{
    /**
     * @param string $direction
     */
    public function sortId(string $direction): void
    {
        $this->builder->orderBy('id', $direction);
    }

    /**
     * @param string $direction
     */
    public function sortName(string $direction): void
    {
        $this->builder->orderBy('name', $direction);
    }
}
