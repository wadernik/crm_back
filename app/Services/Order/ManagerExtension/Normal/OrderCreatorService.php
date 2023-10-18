<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Normal;

use App\DTOs\Order\CreateOrderDTO;
use App\Managers\Order\Normal\OrderManagerInterface;
use App\Models\Order\Order;
use App\Services\Order\ManagerExtension\BaseOrderCreatorServiceInterface;
use function App\Helpers\Functions\load_service;

final class OrderCreatorService implements OrderCreatorServiceInterface
{
    private readonly BaseOrderCreatorServiceInterface $innerService;

    public function __construct()
    {
        $this->innerService = load_service(
            BaseOrderCreatorServiceInterface::class,
            [
                'managerClass' => OrderManagerInterface::class,
                'dtoClass' => CreateOrderDTO::class,
            ]
        );
    }

    public function create(array $attributes): Order
    {
        return $this->innerService->create($attributes);
    }
}