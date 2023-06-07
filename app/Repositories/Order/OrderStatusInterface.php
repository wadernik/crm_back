<?php

declare(strict_types=1);

namespace App\Repositories\Order;

interface OrderStatusInterface
{
    /**
     * @return array<array{
     *     id: int,
     *     name: string
     * }>
     */
    public function statuses(): array;
}