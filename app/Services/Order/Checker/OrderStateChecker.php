<?php

declare(strict_types=1);

namespace App\Services\Order\Checker;

use App\Models\Order\Order;
use App\Models\Order\OrderStatus;

final class OrderStateChecker implements OrderStateCheckerInterface
{
    public function check(Order $order): bool
    {
        return $order->number_external && $order->status === OrderStatus::STATUS_SOLD;
    }
}