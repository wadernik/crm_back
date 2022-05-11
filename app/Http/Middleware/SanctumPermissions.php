<?php

namespace App\Http\Middleware;

use App\Http\Responses\BaseApiResponseTrait;
use Closure;
use Illuminate\Http\Request;

class SanctumPermissions
{
    use BaseApiResponseTrait;

    public function handle(Request $request, Closure $next, ...$permissions)
    {
        foreach ($permissions as $permission) {
            if (!$request->user()->tokenCan($permission)) {
                return $this->responseError(code: 403, message: 'Access denied');
            }
        }

        return $next($request);
    }
}
