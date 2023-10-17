<?php

declare(strict_types=1);

namespace App\Managers\User;

use App\DTOs\User\CreateUserDTOInterface;
use App\DTOs\User\UpdateUserDTOInterface;
use App\Models\User\User;
use App\Models\User\UserToken;
use function bcrypt;

final class UserManager implements UserManagerInterface
{
    public function create(CreateUserDTOInterface $userDTO): User
    {
        $attributes = $userDTO->toArray();

        $attributes['password'] = bcrypt($userDTO->password());

        /** @var User $user */
        $user = User::query()->create($attributes);

        return $user;
    }

    public function update(int $id, UpdateUserDTOInterface $userDTO): ?User
    {
        /** @var User $user */
        if (!$user = User::query()->find($id)) {
            return null;
        }

        $user->update($userDTO->toArray());

        return $user;
    }

    public function delete(int $id): ?User
    {
        /** @var User $user */
        if (!$user = User::query()->find($id)) {
            return null;
        }

        $user->delete();

        return $user;
    }

    public function createToken(int $id, string $accessToken): void
    {
        UserToken::query()->updateOrCreate(
            ['user_id' => $id],
            ['token' => $accessToken]
        );
    }

    public function deleteToken(int $id): void
    {
        UserToken::query()
            ->where('user_id', $id)
            ->forceDelete();
    }
}