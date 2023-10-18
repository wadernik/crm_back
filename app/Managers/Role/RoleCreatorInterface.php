<?php

declare(strict_types=1);

namespace App\Managers\Role;

use App\DTOs\Role\CreateRoleDTOInterface;
use App\Models\Role\Role;

interface RoleCreatorInterface
{
    public function create(CreateRoleDTOInterface $roleDTO): Role;
}