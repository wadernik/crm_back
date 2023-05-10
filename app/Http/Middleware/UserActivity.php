<?php

namespace App\Http\Middleware;

use App\Models\User\User;
use Closure;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        if (auth('sanctum')->check()) {
            $userId = auth('sanctum')->id();

            User::query()
                ->where('id', $userId)
                ->update(['last_seen' => (new DateTime())->format('Y-m-d H:i:s')]);
        }

        return $next($request);
    }
}