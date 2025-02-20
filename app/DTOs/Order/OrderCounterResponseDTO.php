<?php

declare(strict_types=1);

namespace App\DTOs\Order;

use function __;

final class OrderCounterResponseDTO
{
    public function __construct(
        private readonly int $totalWithoutDeliveries,
        private readonly int $totalWithDeliveries,
        private readonly int $totalWithDeliveriesByUser,
    )
    {
    }

    public function getResponseAsArray(): array
    {
        return [
            [
                'id' => 1,
                'label' => __('order.label.store'),
                'total' => $this->totalWithoutDeliveries,
            ],
            [
                'id' => 2,
                'label' => __('order.label.delivery'),
                'total' => $this->totalWithDeliveries,
            ],
            [
                'id' => 3,
                'label' => __('order.label.my_delivery'),
                'total' => $this->totalWithDeliveriesByUser,
            ]
        ];
    }
}