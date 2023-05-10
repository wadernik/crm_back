<?php

declare(strict_types=1);

namespace App\Managers\Role;

use App\DTOs\Role\CreateRoleDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface RoleCreatorInterface
{
    public function create(CreateRoleDTOInterface $roleDTO): Model;
}