<?php

namespace App\Services\Roles;

use App\Models\Role;
use App\Services\BaseInstanceService;
use App\Services\Users\UsersCollectionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class RoleInstanceService extends BaseInstanceService
{
    public function __construct(Role $role)
    {
        $this->modelClass = $role;
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function createInstance(array $attributes): Model
    {
        $params = array_filter([
            'name' => $attributes['name'] ?? null,
            'label' => $attributes['label'] ?? null,
        ]);

        $role = parent::createInstance($params);

        if (isset($attributes['permissions'])) {
            $permissionIds = collect($attributes['permissions'])
                ->pluck('id')
                ->toArray();

            $role->permissions()->sync($permissionIds);
        }

        return $role;
    }

    /**
     * @param int $id
     * @param array $attributes
     * @return Model|null
     */
    public function editInstance(int $id, array $attributes): ?Model
    {
        $params = array_filter([
            'name' => $attributes['name'] ?? null,
            'label' => $attributes['label'] ?? null,
        ]);

        if (!$role = parent::editInstance($id, $params)) {
            return null;
        }

        if (isset($attributes['permissions'])) {
            $permissionIds = collect($attributes['permissions'])
                ->pluck('id')
                ->toArray();

            $role->permissions()->sync($permissionIds);
        }

        return $role;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteInstance(int $id): bool
    {
        $users = app(UsersCollectionService::class)->getInstances(requestParams: ['filter' => ['role_id' => $id]]);

        if (!empty($users)) {
            Log::info('Users with current role were found. Unable to delete this role');
            return false;
        }

        return parent::deleteInstance($id);
    }
}
