<?php

declare(strict_types=1);

namespace App\Services\Order\Checker;

use App\Models\Order\Order;

final class OrderFinalPriceChecker implements OrderFinalPriceCheckerInterface
{
    public function check(Order $order, array $attributes = []): bool
    {
        if (empty($attributes['number_external'])) {
            return true;
        }

        if ($order->number_external === $attributes['number_external']) {
            return true;
        }

        return false;
    }
}