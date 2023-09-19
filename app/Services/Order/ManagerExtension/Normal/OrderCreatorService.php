<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Normal;

use App\DTOs\Order\CreateOrderDTO;
use App\Managers\Order\Normal\OrderManagerInterface;
use App\Services\Order\ManagerExtension\AbstractOrderCreatorService;

final class OrderCreatorService extends AbstractOrderCreatorService implements OrderCreatorServiceInterface
{
    public function __construct()
    {
        parent::__construct(OrderManagerInterface::class, CreateOrderDTO::class);
    }
}