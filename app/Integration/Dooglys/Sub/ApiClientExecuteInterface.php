<?php

declare(strict_types=1);

namespace App\Integration\Dooglys\Sub;

use App\Integration\Dooglys\Response\DooglysResponseInterface;

interface ApiClientExecuteInterface
{
    public function execute(): DooglysResponseInterface;
}