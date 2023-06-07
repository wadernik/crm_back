<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\Models\Order\Order;

final class OrderManager extends AbstractOrderManager
{
    public function __construct()
    {
        parent::__construct(Order::query());
    }
}