<?php

namespace App\Services;

use App\Models\Permission;

class PermissionsService
{
    /**
     * Dictionary
     * @return array
     */
    public function getPermissions(): array
    {
        return Permission::all()->toArray();
    }
}
