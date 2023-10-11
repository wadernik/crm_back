<?php

declare(strict_types=1);

namespace App\Integration\Dooglys\Sub;

interface ApiClientMethodInterface
{
    public function method(string $method): ApiClientOptionsInterface;
}