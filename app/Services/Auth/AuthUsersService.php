<?php

namespace App\Services\Auth;

use App\Models\User\User;
use Illuminate\Support\Facades\Auth;

class AuthUsersService
{
    /**
     * @param array $attributes
     * @param string $deviceName
     * @return string
     */
    public function getToken(array $attributes, string $deviceName = 'auth_token'): string
    {
        if (!Auth::attempt($attributes)) {
            return '';
        }

        /** @var ?User $user */
        $user = User::query()->find($attributes['id']);

        if (!$user) {
            return '';
        }

        $this->revokeTokenByDeviceName($deviceName);

        $userPermissions = $user->getUserPermissions()?->toArray() ?? ['*'];

        return $user
            ->createToken($deviceName, $userPermissions)
            ->plainTextToken;
    }

    /**
     * @param string $deviceName
     */
    public function revokeTokenByDeviceName(string $deviceName): void
    {
        auth('sanctum')->user()?->tokens()->where('name', 'LIKE', $deviceName)->delete();
    }

    /**
     * @param int $id
     */
    public function revokeTokenById(int $id): void
    {
        auth('sanctum')->user()?->tokens()->where('id', $id)->delete();
    }

    public function revokeAllTokens(): void
    {
        auth('sanctum')->user()?->tokens()->delete();
    }
}