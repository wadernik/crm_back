<?php

namespace App\Services\Roles;

use App\ModelModifiers\ModelFilters\RolesFilter;
use App\ModelModifiers\ModelSorts\RolesSort;
use App\Models\Role;
use App\Services\BaseCollectionService;

class RolesCollectionService extends BaseCollectionService
{
    public function __construct(Role $role, RolesFilter $filter, RolesSort $sort)
    {
        $this->modelClass = $role;
        $this->modelFilter = $filter;
        $this->modelSort = $sort;
    }
}
