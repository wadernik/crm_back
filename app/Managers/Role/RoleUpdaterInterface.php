<?php

declare(strict_types=1);

namespace App\Managers\Role;

use App\DTOs\Role\UpdateRoleDTOInterface;
use App\Models\Role\Role;

interface RoleUpdaterInterface
{
    public function update(Role $role, UpdateRoleDTOInterface $roleDTO): Role;
}