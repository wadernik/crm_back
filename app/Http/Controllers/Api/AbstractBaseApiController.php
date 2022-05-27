<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseApiResponseTrait;

abstract class AbstractBaseApiController extends Controller
{
    use BaseApiResponseTrait;

    /**
     * @param string $permission
     * @return bool
     */
    protected function isAllowed(string $permission): bool
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }

        return collect($user->getUserPermissions())->contains($permission);
    }
}
