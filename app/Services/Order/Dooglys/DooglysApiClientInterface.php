<?php

declare(strict_types=1);

namespace App\Services\Order\Dooglys;

use App\Services\Order\Dooglys\Sub\DooglysResponseInterface;

interface DooglysApiClientInterface
{
    public function orders(
        int|string $dateStart,
        int|string $dateEnd,
        string $orderNumber
    ): DooglysResponseInterface;
}