<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension;

use App\Exceptions\OrderException;
use App\Managers\Order\AbstractOrderManagerInterface;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Services\Order\Checker\OrderCreationRestrictionCheckerInterface;
use App\Services\Order\OrderNumber\OrderNumberGeneratorServiceInterface;
use function app;
use function auth;

abstract class AbstractOrderCreatorService implements AbstractOrderCreatorServiceInterface
{
    private AbstractOrderManagerInterface $manager;
    private OrderNumberGeneratorServiceInterface $numberGeneratorService;
    private OrderCreationRestrictionCheckerInterface $orderCreationRestrictionChecker;

    public function __construct(private readonly string $managerClass, private readonly string $dtoClass)
    {
        $this->manager = app($this->managerClass);
        $this->numberGeneratorService = app(OrderNumberGeneratorServiceInterface::class);
        $this->orderCreationRestrictionChecker = app(OrderCreationRestrictionCheckerInterface::class);
    }

    public function create(array $attributes): Order
    {
        if (!$this->orderCreationRestrictionChecker->check(
            $attributes['manufacturer_id'] ?? null,
            $attributes['order_date'] ?? null
        )) {
            throw new OrderException(message: __('order.limited_date'));
        }

        if (!isset($attributes['user_id'])) {
            $attributes['user_id'] = auth('sanctum')->id();
        }

        $attributes['status'] = OrderStatus::STATUS_ACCEPTED;
        $attributes['number'] = $this->numberGeneratorService->generate($attributes['order_date']);

        return $this->manager->create(new $this->dtoClass($attributes));
    }
}