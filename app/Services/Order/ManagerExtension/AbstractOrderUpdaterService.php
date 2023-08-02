<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension;

use App\DTOs\Order\UpdateOrderDTO;
use App\Exceptions\OrderException;
use App\Managers\Order\AbstractOrderManagerInterface;
use App\Models\Order\Order;
use App\Services\Order\Checker\OrderCreateRestrictionCheckerInterface;
use App\Services\Order\Processor\OrderFinalPriceProcessorInterface;
use function app;

abstract class AbstractOrderUpdaterService implements AbstractOrderUpdateServiceInterface
{
    private AbstractOrderManagerInterface $manager;
    private OrderCreateRestrictionCheckerInterface $orderCreateRestrictionChecker;
    private OrderFinalPriceProcessorInterface $finalPriceProcessor;

    public function __construct(private string $managerClass)
    {
        $this->manager = app($this->managerClass);
        $this->orderCreateRestrictionChecker = app(OrderCreateRestrictionCheckerInterface::class);
        $this->finalPriceProcessor = app(OrderFinalPriceProcessorInterface::class);
    }

    public function update(Order $order, array $attributes): ?Order
    {
        if (!$this->orderCreateRestrictionChecker->check(
            $attributes['manufacturer_id'] ?? null,
            $attributes['order_date'] ?? null
        )) {
            throw new OrderException(message: __('order.limited_date'));
        }

        if ($finalPrice = $this->finalPriceProcessor->run($order)) {
            $attributes['price'] = $finalPrice;
        }

        return $this->manager->update($order, new UpdateOrderDTO($attributes));
    }
}