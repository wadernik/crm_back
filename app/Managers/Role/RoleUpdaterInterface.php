<?php

declare(strict_types=1);

namespace App\Managers\Role;

use App\DTOs\Role\UpdateRoleDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface RoleUpdaterInterface
{
    public function update(int $id, UpdateRoleDTOInterface $roleDTO): ?Model;
}