<?php

declare(strict_types=1);

namespace App\Managers\Role;

use App\DTOs\Role\CreateRoleDTOInterface;
use App\DTOs\Role\UpdateRoleDTOInterface;
use App\Models\Role\Role;
use Illuminate\Database\Eloquent\Model;

final class RoleManager implements RoleManagerInterface
{
    public function create(CreateRoleDTOInterface $roleDTO): Model
    {
        return Role::query()->create($roleDTO->toArray());
    }

    public function update(int $id, UpdateRoleDTOInterface $roleDTO): ?Model
    {
        if (!$role = Role::query()->find($id)) {
            return null;
        }

        $role->update($roleDTO->toArray());

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