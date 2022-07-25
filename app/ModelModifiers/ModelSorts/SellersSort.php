<?php

namespace App\ModelModifiers\ModelSorts;

class SellersSort extends AbstractBaseModelSort
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

    /**
     * @param string $direction
     */
    public function sortAddress(string $direction): void
    {
        $this->builder->orderBy($this->getColumnName('address'), $direction);
    }

    /**
     * @param string $direction
     */
    public function sortPhone(string $direction): void
    {
        $this->builder->orderBy($this->getColumnName('phone'), $direction);
    }

    /**
     * @param string $direction
     */
    public function sortEmail(string $direction): void
    {
        $this->builder->orderBy($this->getColumnName('email'), $direction);
    }

    /**
     * @param string $direction
     */
    public function sortWorkingHours(string $direction): void
    {
        $this->builder->orderBy($this->getColumnName('working_hours'), $direction);
    }
}
