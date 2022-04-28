<?php

namespace App\Services\Users;

use App\ModelModifiers\ModelFilters\UsersFilter;
use App\ModelModifiers\ModelSorts\UsersSort;
use App\Models\User;
use App\Services\BaseCollectionService;

class UsersCollectionService extends BaseCollectionService
{
    public function __construct(User $user, UsersFilter $filter, UsersSort $sort)
    {
        $this->modelClass = $user;
        $this->modelFilter = $filter;
        $this->modelSort = $sort;
    }

    /**
     * @param array $attributes
     * @param array $requestParams
     * @param array $with
     * @return array
     */
    public function getInstances(array $attributes = ['*'], array $requestParams = [], array $with = []): array
    {
        $userQuery = User::query();

        $this->applyFilterParams($userQuery, $requestParams, UsersFilter::class);
        $this->applyPageParams($userQuery, $requestParams);

        // Default sorting by first_name for users
        if (!isset($requestParams['sort'])) {
            $requestParams['sort'] = 'first_name';
            $requestParams['order'] = 'asc';
        }

        $this->applySortParams($userQuery, $requestParams, UsersSort::class);

        $users = $userQuery
            ->get($attributes);

        if (in_array('last_seen', $attributes, true)) {
            $users->append('is_online');
        }

        return $users->toArray();
    }
}
