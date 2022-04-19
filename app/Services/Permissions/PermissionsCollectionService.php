<?php

namespace App\Services\Permissions;

use App\ModelFilters\PermissionsFilter;
use App\Models\Permission;
use App\Services\BaseCollectionService;

class PermissionsCollectionService extends BaseCollectionService
{
    public function __construct(Permission $permission, PermissionsFilter $filter)
    {
        $this->modelClass = $permission;
        $this->modelFilter = $filter;
    }
}
