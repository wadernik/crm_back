<?php

declare(strict_types=1);

namespace App\Helpers\Functions;

use function app;

if (!function_exists('load_service')) {
    /**
     * @template T of object
     *
     * @param class-string<T> $serviceClass
     *
     * @return T
     */
    function load_service(string $serviceClass, array $args = []): mixed
    {
        if ($args) {
            return app()->make($serviceClass, $args);
        }

        return app($serviceClass);
    }
}