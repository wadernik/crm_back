<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Normal;

use App\Exceptions\NotSuitableSellerException;
use App\Models\Order\Order;
use App\Services\Order\Checker\OrderSellerCheckerInterface;
use App\Services\Order\ManagerExtension\BaseOrderCreatorServiceInterface;
use function __;

final class OrderCreatorService implements OrderCreatorServiceInterface
{
    public function __construct(
        private readonly BaseOrderCreatorServiceInterface $innerService,
        private readonly OrderSellerCheckerInterface $orderSellerChecker,
    )
    {
    }

    public function create(array $attributes): Order
    {
        if (!$this->orderSellerChecker->check($attributes['seller_id'])) {
            throw new NotSuitableSellerException(message: __('order.not_suitable_seller'));
        }

        return $this->innerService->create($attributes);
    }
}