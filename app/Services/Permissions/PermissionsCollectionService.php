<?php

namespace App\Services\Permissions;

use App\ModelModifiers\ModelFilters\PermissionsFilter;
use App\ModelModifiers\ModelSorts\PermissionsSort;
use App\Models\Permission;
use App\Services\AbstractBaseCollectionService;

class PermissionsCollectionService extends AbstractBaseCollectionService
{
    public function __construct(Permission $permission, PermissionsFilter $filter, PermissionsSort $sort)
    {
        $this->modelClass = $permission;
        $this->modelFilter = $filter;
        $this->modelSort = $sort;
    }
}
