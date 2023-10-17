<?php

declare(strict_types=1);

namespace App\Managers\Role;

use App\Models\Role\Role;

interface RoleDeleterInterface
{
    public function delete(int $id): ?Role;
}