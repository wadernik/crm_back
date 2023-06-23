<?php

declare(strict_types=1);

namespace App\Managers\Role;

use App\DTOs\Role\CreateRoleDTOInterface;
use App\DTOs\Role\UpdateRoleDTOInterface;
use App\Models\Role\Role;
use App\Models\Role\RoleInterface;
use Illuminate\Database\Eloquent\Model;

final class RoleManager implements RoleManagerInterface
{
    public function create(CreateRoleDTOInterface $roleDTO): Model
    {
        /** @var RoleInterface|Model $role */
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

    public function update(int $id, UpdateRoleDTOInterface $roleDTO): ?Model
    {
        /** @var RoleInterface|Model $role */
        if (!$role = Role::query()->find($id)) {
            return null;
        }

        $role->update(array_filter([
            'name' => $roleDTO->name(),
            'label' => $roleDTO->label()
        ]));

        if ($roleDTO->permissions()) {
            $permissions = collect($roleDTO->permissions())->pluck('id')->toArray();

            $role->permissions()->sync($permissions);
        }

        return $role;
    }

    public function delete(int $id): ?Model
    {
        if (!$role = Role::query()->find($id)) {
            return null;
        }

        $role->delete();

        return $role;
    }
}