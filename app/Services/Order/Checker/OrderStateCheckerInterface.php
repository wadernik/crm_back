<?php

declare(strict_types=1);

namespace App\Services\Order\Checker;

use App\Models\Order\Order;

interface OrderStateCheckerInterface
{
    public function check(Order $order): bool;
}