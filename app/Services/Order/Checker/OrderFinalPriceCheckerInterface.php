<?php

declare(strict_types=1);

namespace App\Services\Order\Checker;

use App\Models\Order\Order;

interface OrderFinalPriceCheckerInterface
{
    public function check(Order $order, array $attributes = []): bool;
}