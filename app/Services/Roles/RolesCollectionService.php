<?php

namespace App\Services\Roles;

use App\ModelFilters\RolesFilter;
use App\Models\Role;
use App\Services\BaseCollectionService;

class RolesCollectionService extends BaseCollectionService
{
    public function __construct(Role $role, RolesFilter $filter)
    {
        $this->modelClass = $role;
        $this->modelFilter = $filter;
    }
}
