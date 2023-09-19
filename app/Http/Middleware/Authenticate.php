<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            return route('');
        }

        return null;
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if (auth('sanctum')->guest()) {
            return ApiResponse::responseError(code: Response::HTTP_UNAUTHORIZED, message: "Unauthenticated.");
        }

        return parent::handle($request, $next, ...$guards);
    }
}