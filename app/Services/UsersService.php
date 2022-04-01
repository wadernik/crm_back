<?php

namespace App\Services;

use App\ModelFilters\UsersFilter;
use App\Models\User;
use App\Services\Traits\Filterable;

class UsersService
{
    use Filterable;

    /**
     * @param int $id
     * @param array $attributes
     * @return array
     */
    public function getUser(int $id, array $attributes = ['*']): array
    {
        $user = User::query()->find($id, $attributes);

        return $user ? $user->toArray() : [];
    }

    /**
     * @param array $attributes
     * @param array $requestParams
     * @return array|null
     */
    public function getUsers(array $attributes = ['*'], array $requestParams = []): ?array
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

    public function createUser(array $attributes): ?int
    {
        $user = User::query()
            ->create([
                'first_name' => $attributes['first_name'],
                'last_name' => $attributes['last_name'] ?? null,
                'username' => $attributes['username'],
                'password' => bcrypt($attributes['password']),
                'email' => $attributes['email'] ?? null,
                'phone' => $attributes['phone'] ?? null,
                'birth_date' => $attributes['birth_date'] ?? null,
                'role_id' => $attributes['role_id'],
                // 'remember_token' => $attributes['remember_token'], // Str::random(10),
                // 'last_seen' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]);

        return $user['id'];
    }

    /**
     * @param int $userId
     * @param array $attributes
     * @return int
     */
    public function editUser(int $userId, array $attributes): int
    {
        return User::query()
            ->where('id', $userId)
            ->update($attributes);
    }

    public function deleteUser(int $userId): void
    {
        User::query()
            ->where('id', $userId)
            ->delete();
    }
}
