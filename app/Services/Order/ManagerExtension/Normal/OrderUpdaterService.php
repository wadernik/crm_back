<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Normal;

use App\Models\Order\Order;
use App\Services\Order\ManagerExtension\BaseOrderUpdaterServiceInterface;

final class OrderUpdaterService implements OrderUpdaterServiceInterface
{
    public function __construct(private readonly BaseOrderUpdaterServiceInterface $innerService)
    {
    }

    public function update(Order $order, array $attributes): Order
    {
        return $this->innerService->update($order, $attributes);
    }
}