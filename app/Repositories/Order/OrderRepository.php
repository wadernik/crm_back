<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Models\Order\Order;

final class OrderRepository extends AbstractOrderRepository implements OrderRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Order::query(), OrderFilter::class);
    }
}