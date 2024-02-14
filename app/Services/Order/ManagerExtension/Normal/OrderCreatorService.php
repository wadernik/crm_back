<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Normal;

use App\Models\Order\Order;
use App\Services\Order\ManagerExtension\BaseOrderCreatorServiceInterface;

final class OrderCreatorService implements OrderCreatorServiceInterface
{
    public function __construct(private readonly BaseOrderCreatorServiceInterface $innerService)
    {
    }

    public function create(array $attributes): Order
    {
        return $this->innerService->create($attributes);
    }
}