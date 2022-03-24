<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UsersService
{
    public function createUserAction(array $attributes): ?int
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
            ]);

        return $user['id'];
    }

    public function getAllUsers(array $filterParams = [], array $pageParams = []): ?array
    {
        $userQuery = User::query();

        $userQuery->select(['id', 'first_name', 'last_name', 'email', 'role_id']);

        if (!empty($pageParams)) {
            $userQuery
                ->offset($pageParams['limit'] * ($pageParams['page'] - 1))
                ->limit($pageParams['limit']);
        }

        if (isset($filterParams['name'])) {
            $this->filterName($filterParams['name']);
        }

        if (isset($filterParams['role_id'])) {
            $this->filterRole($filterParams['role_id']);
        }

        return [];
    }

    private function filterName(Builder $query, string $name)
    {
        $query->where('name', 'ILIKE', '%' . $name . '%');
    }

    private function filterRole(Builder $query, int $roleId)
    {
        $query->where('role_id', $roleId);
    }
}
