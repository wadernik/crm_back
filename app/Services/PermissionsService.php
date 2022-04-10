<?php

namespace App\Services;

use App\ModelFilters\PermissionsFilter;
use App\Models\Permission;
use App\Services\Traits\Filterable;

class PermissionsService
{
    use Filterable;

    /**
     * Dictionary
     * @param array $requestParams
     * @return array
     */
    public function getPermissions(array $requestParams = []): array
    {
        $permissionsQuery = Permission::query();

        $this->applyFilterParams($permissionsQuery, $requestParams, PermissionsFilter::class);
        $this->applyPageParams($permissionsQuery, $requestParams);

        return $permissionsQuery
            ->get()
            ->toArray();
    }
}
