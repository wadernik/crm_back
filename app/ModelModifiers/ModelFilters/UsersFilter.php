<?php

namespace App\ModelModifiers\ModelFilters;

use App\Models\User;
use Carbon\Carbon;

class UsersFilter extends AbstractBaseModelFilter
{
    /**
     * @param int $id
     */
    public function filterId(int $id): void
    {
        $this->builder->where($this->getColumnName('id'), $id);
    }

    public function filterIds(array $ids): void
    {
        $this->builder->whereIn($this->getColumnName('id'), $ids);
    }

    /**
     * @param string $name
     */
    public function filterFirstName(string $name): void
    {
        $this->builder->where($this->getColumnName('first_name'), 'LIKE', "%$name%");
    }

    /**
     * @param string $name
     */
    public function filterLastName(string $name): void
    {
        $this->builder->where($this->getColumnName('last_name'), 'LIKE', "%$name%");
    }

    /**
     * @param int $roleId
     */
    public function filterRoleId(int $roleId): void
    {
        $this->builder->where($this->getColumnName('role_id'), $roleId);
    }

    /**
     * @param array $roleIds
     */
    public function filterRoleIds(array $roleIds): void
    {
        $this->builder->whereIn($this->getColumnName('role_id'), $roleIds);
    }

    /**
     * @param bool $isOnline
     */
    public function filterIsOnline(bool $isOnline): void
    {
        $now = Carbon::now()->subMinutes(User::ONLINE_STATUS_BORDER)->format('Y-m-d H:i:s');
        $lastSeenColumn = $this->getColumnName('last_seen');

        if ($isOnline) {
            $this->builder
                ->whereNotNull($lastSeenColumn)
                ->where($lastSeenColumn, '>=', $now);
        } else {
            $this->builder
                ->where($lastSeenColumn, '<=', $now)
                ->orWhereNull($lastSeenColumn);
        }
    }
}
