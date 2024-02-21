<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Normal;

use App\Exceptions\NotSuitableSellerException;
use App\Models\Order\Order;
use App\Services\Order\Checker\OrderSellerCheckerInterface;
use App\Services\Order\ManagerExtension\BaseOrderUpdaterServiceInterface;
use function __;

final class OrderUpdaterService implements OrderUpdaterServiceInterface
{
    public function __construct(
        private readonly BaseOrderUpdaterServiceInterface $innerService,
        private readonly OrderSellerCheckerInterface $orderSellerChecker,
    )
    {
    }

    public function update(Order $order, array $attributes): Order
    {
        $sellerId = $attributes['seller_id'] ?? null;

        if ($sellerId && !$this->orderSellerChecker->check($sellerId)) {
            throw new NotSuitableSellerException(message: __('order.not_suitable_seller'));
        }

        return $this->innerService->update($order, $attributes);
    }
}