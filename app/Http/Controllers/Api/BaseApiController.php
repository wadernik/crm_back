<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseApiResponse;

abstract class BaseApiController extends Controller
{
    use BaseApiResponse;

    protected function isAllowed(string $permission): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        return collect($user->getUserPermissions())->contains($permission);
    }
}
