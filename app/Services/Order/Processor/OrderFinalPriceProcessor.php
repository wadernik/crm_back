<?php

declare(strict_types=1);

namespace App\Services\Order\Processor;

use App\Models\Order\Order;
use App\Services\Dooglys\Order\DooglysOrderSyncServiceInterface;
use App\Services\Order\Checker\OrderStateCheckerInterface;

final class OrderFinalPriceProcessor implements OrderFinalPriceProcessorInterface
{
    public function __construct(
        private readonly DooglysOrderSyncServiceInterface $dooglysService,
        private readonly OrderStateCheckerInterface $orderStateChecker
    )
    {
    }

    public function run(Order $order): int
    {
        if (!$this->orderStateChecker->check($order)) {
            return 0;
        }

        return $this->dooglysService->finalPrice(
            $order->created_at,
            $order->number_external
        );
    }
}