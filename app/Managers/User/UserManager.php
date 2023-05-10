<?php

declare(strict_types=1);

namespace App\Managers\User;

use App\DTOs\User\CreateUserDTOInterface;
use App\DTOs\User\UpdateUserDTOInterface;
use App\Models\User\User;

use Illuminate\Database\Eloquent\Model;

use function bcrypt;

final class UserManager implements UserManagerInterface
{
    public function create(CreateUserDTOInterface $userDTO): Model
    {
        $attributes = $userDTO->toArray();

        $attributes['password'] = bcrypt($userDTO->password());

        return User::query()->create($attributes);
    }

    public function update(int $id, UpdateUserDTOInterface $userDTO): ?Model
    {
        if (!$user = User::query()->find($id)) {
            return null;
        }

        $user->update($userDTO->toArray());

        return $user;
    }

    public function delete(int $id): ?Model
    {
        if (!$user = User::query()->find($id)) {
            return null;
        }

        $user->delete();

        return $user;
    }
}