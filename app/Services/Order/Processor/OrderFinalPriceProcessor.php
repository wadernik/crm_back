<?php

declare(strict_types=1);

namespace App\Services\Order\Processor;

use App\Models\Order\Order;
use App\Services\Order\Dooglys\DooglysServiceInterface;
use App\Services\Order\Checker\OrderStateCheckerInterface;
use function app;

final class OrderFinalPriceProcessor implements OrderFinalPriceProcessorInterface
{
    private DooglysServiceInterface $dooglysService;
    private OrderStateCheckerInterface $orderStateChecker;

    public function __construct()
    {
        $this->dooglysService = app(DooglysServiceInterface::class);
        $this->orderStateChecker = app(OrderStateCheckerInterface::class);
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