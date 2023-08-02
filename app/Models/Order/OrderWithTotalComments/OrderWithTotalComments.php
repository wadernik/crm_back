<?php

declare(strict_types=1);

namespace App\Models\Order\OrderWithTotalComments;

use App\Models\Order\Order;

final class OrderWithTotalComments implements OrderWithTotalCommentsInterface
{
    public function __construct(private Order $order, private int $total)
    {
    }

    public function order(): Order
    {
        return $this->order;
    }

    public function totalComments(): int
    {
        return $this->total;
    }

    public function toArray(): array
    {
        $order = $this->order->toArray();

        return array_merge($order, ['total_comments' => $this->total]);
    }
}