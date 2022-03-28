<?php

namespace App\Services;

use App\ModelFilters\UsersFilter;
use App\Models\User;
use App\Services\Traits\Filterable;

class UsersService
{
    use Filterable;

    public function createUser(array $attributes): ?int
    {
        $user = User::query()
            ->create([
                'first_name' => $attributes['first_name'],
                'last_name' => $attributes['last_name'],
                'username' => $attributes['username'],
                'password' => bcrypt($attributes['password']),
                'email' => $attributes['email'],
                'phone' => $attributes['phone'],
                // 'remember_token' => $attributes['remember_token'], // Str::random(10),
                'role_id' => $attributes['role_id'],
                'last_seen' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]);

        return $user['id'];
    }

    /**
     * Retrieve users with pagination
     * @param array $requestParams
     * @return array|null
     */
    public function getUsers(array $requestParams = []): ?array
    {
        $userQuery = User::query()
            ->select(['id', 'first_name', 'last_name', 'email', 'role_id']);

        $this->applyFilterParams($userQuery, $requestParams, UsersFilter::class);
        $this->applyPageParams($userQuery, $requestParams);

        return $userQuery
            ->get()
            ->toArray();
    }
}
