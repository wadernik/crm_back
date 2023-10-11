<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Order;

interface DooglysOrderSyncServiceInterface
{
    public function finalPrice(string $date, string $orderNumber): int;
}