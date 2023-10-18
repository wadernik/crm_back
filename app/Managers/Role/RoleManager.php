<?php

declare(strict_types=1);

namespace App\Managers\Role;

use App\DTOs\Role\CreateRoleDTOInterface;
use App\DTOs\Role\UpdateRoleDTOInterface;
use App\Models\Role\Role;
use function array_filter;
use function collect;

final class RoleManager implements RoleManagerInterface
{
    public function create(CreateRoleDTOInterface $roleDTO): Role
    {
        /** @var Role $role */
        $role = Role::query()->create(array_filter([
            'name' => $roleDTO->name(),
            'label' => $roleDTO->label(),
        ]));

        if ($roleDTO->permissions()) {
            $permissions = collect($roleDTO->permissions())->pluck('id')->toArray();

            $role->permissions()->sync($permissions);
        }

        return $role;
    }

    public function update(int $id, UpdateRoleDTOInterface $roleDTO): ?Role
    {
        /** @var Role $role */
        if (!$role = Role::query()->find($id)) {
            return null;
        }

        $role->update(array_filter([
            'name' => $roleDTO->name(),
            'label' => $roleDTO->label()
        ]));

        $permissions = $roleDTO->permissions()
            ? collect($roleDTO->permissions())->pluck('id')->toArray()
            : [];

        $role->permissions()->sync($permissions);

        return $role;
    }

    public function delete(int $id): ?Role
    {
        /** @var Role $role */
        if (!$role = Role::query()->find($id)) {
            return null;
        }

        $role->delete();

        return $role;
    }
}