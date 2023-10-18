<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension;

use App\Exceptions\OrderException;
use App\Managers\Order\BaseOrderManagerInterface;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Services\Order\Checker\OrderCreationRestrictionCheckerInterface;
use App\Services\Order\OrderNumber\OrderNumberGeneratorServiceInterface;
use function App\Helpers\Functions\load_service;
use function auth;

final class BaseOrderCreatorService implements BaseOrderCreatorServiceInterface
{
    private BaseOrderManagerInterface $manager;
    private OrderNumberGeneratorServiceInterface $numberGeneratorService;
    private OrderCreationRestrictionCheckerInterface $orderCreationRestrictionChecker;

    public function __construct(private readonly string $managerClass, private readonly string $dtoClass)
    {
        $this->manager = load_service($this->managerClass);
        $this->numberGeneratorService = load_service(OrderNumberGeneratorServiceInterface::class);
        $this->orderCreationRestrictionChecker = load_service(OrderCreationRestrictionCheckerInterface::class);
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