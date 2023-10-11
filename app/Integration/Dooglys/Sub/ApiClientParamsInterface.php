<?php

declare(strict_types=1);

namespace App\Integration\Dooglys\Sub;

interface ApiClientParamsInterface
{
    public function params(array $params): ApiClientOptionsInterface;
}