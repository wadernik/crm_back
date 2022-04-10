<?php

namespace App\Services;

use App\ModelFilters\RolesFilter;
use App\Models\Role;
use App\Services\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

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
        $this->applyPageParams($rolesQuery, $requestParams);

        return $rolesQuery
            ->get()
            ->load($with)
            ->toArray();
    }

    /**
     * @param array $attributes
     * @return int|null
     */
    public function createRole(array $attributes): int|null
    {
        $role = Role::query()->create($attributes);

        return $role['id'] ?? null;
    }

    /**
     * @param int $id
     * @param array $attributes
     * @return bool
     */
    public function editRole(int $id, array $attributes): bool
    {
        if (!$role = Role::query()->find($id)) {
            return false;
        }

        $params = array_filter([
            'name' => $attributes['name'],
            'label' => $attributes['label'],
        ]);

        $role->update($params);

        if (isset($attributes['permissions'])) {
            $this->syncPermissions($role, $attributes['permissions']);
        }

        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteRole(int $id): bool
    {
        $users = (new UsersService())->getUsers(requestParams: ['filter' => ['role_id' => $id]]);

        if (!empty($users)) {
            Log::info('Users with current role were found. Unable to delete this role');
            return false;
        }

        if (!$role = Role::query()->find($id)) {
            return false;
        }

        $role->delete();

        return true;
    }

    /**
     * @param Builder $role
     * @param array $permissions
     */
    public function syncPermissions(Builder $role, array $permissions): void
    {
        $role->permissions()->sync($permissions);
    }
}
