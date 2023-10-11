<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Sub;

use App\Integration\Dooglys\DooglysApiClientInterface;
use function App\Helpers\Functions\load_service;
use function config;

abstract class AbstractDooglysAction
{
    protected DooglysApiClientInterface $apiClient;

    public function __construct()
    {
        $this->apiClient = load_service(
            DooglysApiClientInterface::class,
            ['api' => config('dooglys.api_url'), 'token' => config('dooglys.access_token')]
        );
    }
}