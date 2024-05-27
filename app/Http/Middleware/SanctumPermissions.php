<?php

namespace App\Http\Middleware;

use App\Attributes\Permission;
use App\Http\Responses\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use ReflectionClass;

class SanctumPermissions
{
    public function handle(Request $request, Closure $next)
    {
        $controllerClass = $request->route()->controller::class;

        $reflector = new ReflectionClass($controllerClass);

        foreach($reflector->getAttributes(Permission::class) as $attribute) {
            $permission = $attribute->getArguments()[0] ?? null;

            if (!$permission) {
                continue;
            }

            if (!$request->user()->tokenCan($permission)) {
                return ApiResponse::responseError(code: 403, message: 'Access denied');
            }
        }

        return $next($request);
    }
}