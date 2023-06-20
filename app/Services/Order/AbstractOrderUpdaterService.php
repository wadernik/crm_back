<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\DTOs\Order\UpdateOrderDTO;
use App\Exceptions\OrderException;
use App\Managers\Order\OrderManagerInterface;
use App\Models\Order\OrderInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\DateLimit\AcceptOrderValidatorServiceInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractOrderUpdaterService implements OrderUpdaterServiceInterface
{
    private OrderManagerInterface $manager;
    // private OrderRepositoryInterface $repository;
    private AcceptOrderValidatorServiceInterface $orderValidatorService;

    public function __construct(private string $managerClass)
    {
        $this->manager = app($this->managerClass);
        // $this->repository = app(OrderRepositoryInterface::class);
        $this->orderValidatorService = app(AcceptOrderValidatorServiceInterface::class);
    }

    /**
     * @throws OrderException
     */
    public function update(int $id, array $attributes): ?Model
    {
        $orderDate = $attributes['order_date'] ?? null;
        $manufacturerId = $attributes['manufacturer_id'] ?? null;

        if (!$orderDate || !$manufacturerId) {
            // /** @var OrderInterface $order */
            // $order = $this->repository->find($id);
            //
            // $orderDate = $orderDate ?: $order->order_date;
            // $manufacturerId = $manufacturerId ?: $order->manufacturer_id;
            return null;
        }

        if (!$this->orderValidatorService->canAccept($manufacturerId, $orderDate)) {
            throw new OrderException(message: __('order.limited_date'));
        }

        return $this->manager->update($id, new UpdateOrderDTO($attributes));
    }
}