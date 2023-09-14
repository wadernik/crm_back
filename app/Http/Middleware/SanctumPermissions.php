<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class SanctumPermissions
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        foreach ($permissions as $permission) {
            if (!$request->user()->tokenCan($permission)) {
                return ApiResponse::responseError(code: 403, message: 'Access denied');
            }
        }

        return $next($request);
    }
}