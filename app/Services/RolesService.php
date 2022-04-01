<?php

namespace App\Services;

use App\ModelFilters\RolesFilter;
use App\Models\Role;
use App\Services\Traits\Filterable;

class RolesService
{
    use Filterable;

    /**
     * @param int $id
     * @param array $with
     * @return array
     */
    public function getRole(int $id, array $with = []): array
    {
        $role = Role::query()->find($id);

        return $role ? $role->load($with)->toArray() : [];
    }

    /**
     * @param array $requestParams
     * @param array $with
     * @return array
     */
    public function getRoles(array $requestParams = [], array $with = []): array
    {
        $rolesQuery = Role::query();

        $this->applyFilterParams($rolesQuery, $requestParams, RolesFilter::class);

        return $rolesQuery
            ->get()
            ->load($with)
            ->toArray();
    }

    public function createRole(array $attributes): int|null
    {
        $role = Role::query()->create($attributes);
        return $role['id'] ?? '';
    }

    /**
     * @param int $id
     * @param array $permissions
     */
    public function setPermissions(int $id, array $permissions): void
    {
        $role = Role::query()->find($id);
        $role->permissions()->sync($permissions);
    }
}
