<?php

declare(strict_types=1);

namespace App\Managers\Order\Normal;

use App\DTOs\Order\CreateOrderDTOInterface;
use App\DTOs\Order\UpdateOrderDTOInterface;
use App\Events\Order\OrderEntityEvent;
use App\Events\Order\OrderEntityEventTypeEnum;
use App\Managers\Order\AbstractOrderManager;
use App\Models\Order\Order;

final class OrderManager extends AbstractOrderManager implements OrderManagerInterface
{
    public function __construct()
    {
        parent::__construct(draft: false);
    }

    public function create(CreateOrderDTOInterface $orderDTO): Order
    {
        $order = parent::create($orderDTO);

        OrderEntityEvent::dispatch($order, OrderEntityEventTypeEnum::CREATED);

        return $order;
    }

    public function update(Order $order, UpdateOrderDTOInterface $orderDTO): Order
    {
        $updatedOrder = parent::update($order, $orderDTO);

        OrderEntityEvent::dispatch($order, OrderEntityEventTypeEnum::UPDATED);

        return $updatedOrder;
    }
}