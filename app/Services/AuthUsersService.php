<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthUsersService
{
    public function getToken(array $attributes): string
    {
        if (!Auth::attempt($attributes)) {
            return '';
        }

        // $user = User::query()
        //     ->where('username', $attributes['username'])
        //     ->first();

        $user = User::query()->find($attributes['id']);

        if (!$user) {
            return '';
        }

        $user->tokens()->delete();

        $userPermissions = $user->getUserPermissions() ?? ['*'];

        return $user
            ->createToken('auth_token', $userPermissions)
            ->plainTextToken;
    }

    public function revokeToken(): void
    {
        auth()->user()->currentAccessToken()->delete();
    }

    public function revokeAllTokens(): void
    {
        auth()->user()->tokens()->delete();
    }

    public function refreshToken(): string
    {
        $user = auth()->user();

        if (!$user) {
            return '';
        }

        $this->revokeAllTokens();

        $userPermissions = $user->getUserPermissions() ?? ['*'];

        return $user
            ->createToken('auth_token', $userPermissions)
            ->plainTextToken;
    }
}
