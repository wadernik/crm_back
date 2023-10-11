<?php

declare(strict_types=1);

namespace App\Integration\Dooglys\Sub;

interface ApiClientRequestInterface
{
    public function request(string $action): ApiClientOptionsInterface;
}