<?php

namespace App\ModelModifiers\ModelSorts;

class ManufacturersSort extends BaseModelSort
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

    /**
     * @param string $direction
     */
    public function sortAddress(string $direction): void
    {
        $this->builder->orderBy('address', $direction);
    }

    /**
     * @param string $direction
     */
    public function sortPhone(string $direction): void
    {
        $this->builder->orderBy('phone', $direction);
    }

    /**
     * @param string $direction
     */
    public function sortEmail(string $direction): void
    {
        $this->builder->orderBy('email', $direction);
    }

    /**
     * @param string $direction
     */
    public function sortLimit(string $direction): void
    {
        $this->builder->orderBy('limit', $direction);
    }
}
