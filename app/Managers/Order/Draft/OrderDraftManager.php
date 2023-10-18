<?php

declare(strict_types=1);

namespace App\Managers\Order\Draft;

use App\DTOs\Order\CreateOrderDTOInterface;
use App\DTOs\Order\UpdateOrderDTOInterface;
use App\Managers\Order\BaseOrderManagerInterface;
use App\Models\Order\Order;
use function App\Helpers\Functions\load_service;

final class OrderDraftManager implements OrderDraftManagerInterface
{
    private readonly BaseOrderManagerInterface $innerManager;

    public function __construct()
    {
        $this->innerManager = load_service(BaseOrderManagerInterface::class, ['draft' => true]);
    }

    public function create(CreateOrderDTOInterface $orderDTO): Order
    {
        return $this->innerManager->create($orderDTO);
    }

    public function update(Order $order, UpdateOrderDTOInterface $orderDTO): Order
    {
        return $this->innerManager->update($order, $orderDTO);
    }

    public function delete(Order $order): Order
    {
        return $this->innerManager->delete($order);
    }
}