<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Normal;

use App\DTOs\Order\UpdateOrderDTO;
use App\Managers\Order\Normal\OrderManagerInterface;
use App\Models\Order\Order;
use App\Services\Order\ManagerExtension\BaseOrderUpdaterServiceInterface;
use function App\Helpers\Functions\load_service;

final class OrderUpdaterService implements OrderUpdaterServiceInterface
{
    private readonly BaseOrderUpdaterServiceInterface $innerService;

    public function __construct()
    {
        $this->innerService = load_service(
            BaseOrderUpdaterServiceInterface::class,
            [
                'managerClass' => OrderManagerInterface::class,
                'dtoClass' => UpdateOrderDTO::class,
            ]
        );
    }

    public function update(Order $order, array $attributes): Order
    {
        return $this->innerService->update($order, $attributes);
    }
}