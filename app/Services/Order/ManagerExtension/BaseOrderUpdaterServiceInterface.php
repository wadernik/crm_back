<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension;

use App\Models\Order\Order;
use Throwable;

interface BaseOrderUpdaterServiceInterface
{
    /**
     * @param Order $order
     * @param array $attributes
     *
     * @return Order
     *
     * @throws Throwable
     */
    public function update(Order $order, array $attributes): Order;
}