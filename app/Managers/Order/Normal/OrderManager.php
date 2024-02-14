<?php

declare(strict_types=1);

namespace App\Managers\Order\Normal;

use App\DTOs\Order\CreateOrderDTOInterface;
use App\DTOs\Order\UpdateOrderDTOInterface;
use App\Events\Order\OrderEntityEvent;
use App\Events\Order\OrderEntityEventTypeEnum;
use App\Managers\Order\BaseOrderManagerInterface;
use App\Models\Order\Order;

final class OrderManager implements OrderManagerInterface
{
    public function __construct(private readonly BaseOrderManagerInterface $innerManager)
    {
    }

    public function create(CreateOrderDTOInterface $orderDTO): Order
    {
        $order = $this->innerManager->create($orderDTO);

        OrderEntityEvent::dispatch($order, OrderEntityEventTypeEnum::CREATED);

        return $order;
    }

    public function update(Order $order, UpdateOrderDTOInterface $orderDTO): Order
    {
        $updatedOrder = $this->innerManager->update($order, $orderDTO);

        OrderEntityEvent::dispatch($order, OrderEntityEventTypeEnum::UPDATED);

        return $updatedOrder;
    }

    public function delete(Order $order): Order
    {
        return $this->innerManager->delete($order);
    }

    public function remove(Order $order): Order
    {
        return $this->innerManager->remove($order);
    }
}