<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension;

use App\Models\Order\Order;
use Throwable;

interface AbstractOrderCreatorServiceInterface
{
    /**
     * @param array $attributes
     *
     * @return Order
     *
     * @throws Throwable
     */
    public function create(array $attributes): Order;
}