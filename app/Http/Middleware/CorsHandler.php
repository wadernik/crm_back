<?php

namespace App\Http\Middleware;

use Closure;

class CorsHandler
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT,PATCH,DELETE');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, X-Socket-ID');
        header('Access-Control-Expose-Headers: X-Total-Count, X-Time');
        header('Access-Control-Max-Age: 86400');

        return $next($request);
    }
}
