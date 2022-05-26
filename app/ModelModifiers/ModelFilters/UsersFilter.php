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
        $this->builder->where('id', $id);
    }

    public function filterIds(array $ids): void
    {
        $this->builder->whereIn('id', $ids);
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

    /**
     * @param array $roleIds
     */
    public function filterRoleIds(array $roleIds): void
    {
        $this->builder->whereIn('role_id', $roleIds);
    }

    /**
     * @param bool $isOnline
     */
    public function filterIsOnline(bool $isOnline): void
    {
        $now = Carbon::now()->subMinutes(User::ONLINE_STATUS_BORDER)->format('Y-m-d H:i:s');

        if ($isOnline) {
            $this->builder
                ->whereNotNull('last_seen')
                ->where('last_seen', '>=', $now);
        } else {
            $this->builder
                ->where('last_seen', '<=', $now)
                ->orWhereNull('last_seen');
        }
    }
}
