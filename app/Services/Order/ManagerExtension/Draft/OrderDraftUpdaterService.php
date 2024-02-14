<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Draft;

use App\Models\Order\Order;
use App\Services\Order\ManagerExtension\BaseOrderUpdaterServiceInterface;

final class OrderDraftUpdaterService implements OrderDraftUpdaterServiceInterface
{
    public function __construct(private readonly BaseOrderUpdaterServiceInterface $innerService)
    {
    }

    public function update(Order $order, array $attributes): Order
    {
        return $this->innerService->update($order, $attributes);
    }
}