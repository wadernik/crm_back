<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension;

use App\DTOs\Order\UpdateOrderDTO;
use App\Exceptions\OrderException;
use App\Jobs\SyncOrderFinalPriceJob;
use App\Managers\Order\AbstractOrderManagerInterface;
use App\Models\Order\Order;
use App\Services\Order\Checker\OrderCreationRestrictionCheckerInterface;
use App\Services\Order\Checker\OrderFinalPriceCheckerInterface;
use function app;

abstract class AbstractOrderUpdaterService implements AbstractOrderUpdateServiceInterface
{
    private AbstractOrderManagerInterface $manager;
    private OrderCreationRestrictionCheckerInterface $orderCreationRestrictionChecker;
    private OrderFinalPriceCheckerInterface $finalPriceChecker;

    public function __construct(private readonly string $managerClass)
    {
        $this->manager = app($this->managerClass);
        $this->orderCreationRestrictionChecker = app(OrderCreationRestrictionCheckerInterface::class);
        $this->finalPriceChecker = app(OrderFinalPriceCheckerInterface::class);
    }

    public function update(Order $order, array $attributes): ?Order
    {
        if (!$this->orderCreationRestrictionChecker->check(
            $attributes['manufacturer_id'] ?? null,
            $attributes['order_date'] ?? null
        )) {
            throw new OrderException(message: __('order.limited_date'));
        }

        if (!$this->finalPriceChecker->check($order, $attributes)) {
            SyncOrderFinalPriceJob::dispatch($order);
        }

        return $this->manager->update($order, new UpdateOrderDTO($attributes));
    }
}