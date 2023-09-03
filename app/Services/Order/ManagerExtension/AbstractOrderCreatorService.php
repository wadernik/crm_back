<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension;

use App\DTOs\Order\CreateOrderDTO;
use App\Exceptions\OrderException;
use App\Managers\Order\AbstractOrderManagerInterface;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Services\Order\Checker\OrderCreationRestrictionCheckerInterface;
use App\Services\Order\OrderNumber\OrderNumberGeneratorServiceInterface;

abstract class AbstractOrderCreatorService implements AbstractOrderCreatorServiceInterface
{
    private AbstractOrderManagerInterface $manager;
    private OrderNumberGeneratorServiceInterface $numberGeneratorService;
    private OrderCreationRestrictionCheckerInterface $orderValidatorService;

    public function __construct(private readonly string $managerClass)
    {
        $this->manager = app($this->managerClass);
        $this->numberGeneratorService = app(OrderNumberGeneratorServiceInterface::class);
        $this->orderValidatorService = app(OrderCreationRestrictionCheckerInterface::class);
    }

    public function create(array $attributes): Order
    {
        if (!$this->orderValidatorService->check($attributes['manufacturer_id'], $attributes['order_date'])) {
            throw new OrderException(message: __('order.limited_date'));
        }

        if (!isset($attributes['user_id'])) {
            $attributes['user_id'] = auth('sanctum')->id();
        }

        $attributes['status'] = OrderStatus::STATUS_ACCEPTED;
        $attributes['number'] = $this->numberGeneratorService->generate($attributes['order_date']);

        return $this->manager->create(new CreateOrderDTO($attributes));
    }
}