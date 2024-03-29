<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\DTOs\Order\UpdateOrderDTOInterface;
use App\Models\Order\Order;

interface OrderUpdaterInterface
{
    public function update(Order $order, UpdateOrderDTOInterface $orderDTO): Order;
}