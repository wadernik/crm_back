<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Draft;

use App\Models\Order\Order;
use App\Services\Order\ManagerExtension\BaseOrderCreatorServiceInterface;

final class OrderDraftCreatorService implements OrderDraftCreatorServiceInterface
{
    public function __construct(private readonly BaseOrderCreatorServiceInterface $innerService)
    {
    }

    public function create(array $attributes): Order
    {
        return $this->innerService->create($attributes);
    }
}