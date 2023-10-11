<?php

declare(strict_types=1);

namespace App\Services\Order\Processor;

use App\Models\Order\Order;
use App\Services\Dooglys\Order\DooglysOrderSyncServiceInterface;
use App\Services\Order\Checker\OrderStateCheckerInterface;
use function App\Helpers\Functions\load_service;

final class OrderFinalPriceProcessor implements OrderFinalPriceProcessorInterface
{
    private DooglysOrderSyncServiceInterface $dooglysService;
    private OrderStateCheckerInterface $orderStateChecker;

    public function __construct()
    {
        $this->dooglysService = load_service(DooglysOrderSyncServiceInterface::class);
        $this->orderStateChecker = load_service(OrderStateCheckerInterface::class);
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