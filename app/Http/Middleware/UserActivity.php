<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('sanctum')->check()) {
            $userId = auth('sanctum')->user()?->id;

            User::query()
                ->where('id', $userId)
                ->update(['last_seen' => (new \DateTime())->format('Y-m-d H:i:s')]);
        }

        return $next($request);
    }
}
