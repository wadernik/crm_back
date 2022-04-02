<?php

namespace App\ModelFilters;

class UsersFilter extends BaseModelFilter
{
    /**
     * @param int $id
     */
    public function filterId(int $id): void
    {
        $this->builder->where('id', $id);
    }

    /**
     * @param string $name
     */
    public function filterFirstName(string $name): void
    {
        $this->builder->where('first_name', 'LIKE', "%{$name}%");
    }

    /**
     * @param string $name
     */
    public function filterLastName(string $name): void
    {
        $this->builder->where('last_name', 'LIKE', "%{$name}%");
    }

    /**
     * @param int $roleId
     */
    public function filterRoleId(int $roleId): void
    {
        $this->builder->where('role_id', $roleId);
    }
}
