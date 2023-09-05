<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use function auth;
use function collect;

abstract class AbstractApiController extends Controller
{
    /**
     * @param string $permission
     * @return bool
     */
    protected function isAllowed(string $permission): bool
    {
        /** @var User $user */
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }

        return collect($user->getUserPermissions())->contains($permission);
    }

    protected function userId(): int
    {
        return auth('sanctum')->id();
    }

    protected function user(): User
    {
        /** @var User $user */
        $user = auth('sanctum')->user();

        return $user;
    }
}