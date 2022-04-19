<?php

namespace App\Services\Users;

use App\ModelFilters\UsersFilter;
use App\Models\User;
use App\Services\BaseCollectionService;

class UsersCollectionService extends BaseCollectionService
{
    public function __construct(User $user, UsersFilter $filter)
    {
        $this->modelClass = $user;
        $this->modelFilter = $filter;
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

        $users = $userQuery
            ->get($attributes)
            ->makeVisible($attributes);

        if (in_array('last_seen', $attributes, true)) {
            $users->append('is_online');
        }

        return $users->toArray();
    }
}
