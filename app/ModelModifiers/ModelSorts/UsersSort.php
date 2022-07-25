<?php

namespace App\ModelModifiers\ModelSorts;

class UsersSort extends AbstractBaseModelSort
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
    public function sortFirstName(string $direction): void
    {
        $this->builder->orderBy($this->getColumnName('first_name'), $direction);
    }
}
