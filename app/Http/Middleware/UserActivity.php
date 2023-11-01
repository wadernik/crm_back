<?php

namespace App\Http\Middleware;

use App\Helpers\UserCacheKeys;
use Closure;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use function auth;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     *
     * @return Response|RedirectResponse|JsonResponse|BinaryFileResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse|BinaryFileResponse
    {
        if (auth('sanctum')->check()) {
            $lastSeen = (new DateTime())->format('Y-m-d H:i:s');

            if (Cache::has(UserCacheKeys::USER_ONLINE)) {
                $values = Cache::get(UserCacheKeys::USER_ONLINE);
            }

            $values[auth('sanctum')->id()] = $lastSeen;

            Cache::put(UserCacheKeys::USER_ONLINE, $values);
        }

        return $next($request);
    }
}