<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\DTOs\Order\CreateOrderDTOInterface;
use App\Models\Order\Order;

interface OrderCreatorInterface
{
    public function create(CreateOrderDTOInterface $orderDTO): Order;
}