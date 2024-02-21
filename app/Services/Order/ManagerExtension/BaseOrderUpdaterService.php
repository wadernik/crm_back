<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension;

use App\Exceptions\OrderException;
use App\Jobs\SyncOrderFinalPriceJob;
use App\Managers\Order\BaseOrderManagerInterface;
use App\Models\Order\Order;
use App\Services\Order\Checker\OrderCreationRestrictionByManufacturerCheckerInterface;
use App\Services\Order\Checker\OrderFinalPriceCheckerInterface;
use function __;

final class BaseOrderUpdaterService implements BaseOrderUpdaterServiceInterface
{
    public function __construct(
        private readonly BaseOrderManagerInterface $manager,
        private readonly OrderCreationRestrictionByManufacturerCheckerInterface $orderCreationRestrictionChecker,
        private readonly OrderFinalPriceCheckerInterface $finalPriceChecker,
        private readonly string $dtoClass
    )
    {
    }

    public function update(Order $order, array $attributes): Order
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

        // TODO: check if user_id or inspector_id is set on $order

        return $this->manager->update($order, new $this->dtoClass($attributes));
    }
}