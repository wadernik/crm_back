<?php

namespace App\Http\Middleware;

use App\Http\Responses\BaseApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SanctumPermissions
{
    use BaseApiResponse;

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
