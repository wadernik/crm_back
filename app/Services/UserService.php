<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function createUserAction(array $attributes): void
    {
        User::query()
            ->create([
                'name' => $attributes['name'],
                'password' => bcrypt($attributes['password']),
                'email' => $attributes['email']
            ]);
    }

    public function getToken(array $attributes): string
    {
        if (!Auth::attempt($attributes)) {
            return '';
        }

        $user = User::query()
            ->where('username', $attributes['username'])
            ->firstOrFail();

        if (!$user) {
            return '';
        }

        $user->tokens()->delete();

        $userPermissions = $user;

        Log::info($userPermissions);

        return $user
            ->createToken('auth_token')
            ->plainTextToken;
    }
}
