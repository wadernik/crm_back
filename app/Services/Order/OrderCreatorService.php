<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Managers\Order\OrderManagerInterface;

final class OrderCreatorService extends AbstractOrderCreatorService
{
    public function __construct()
    {
        parent::__construct(OrderManagerInterface::class);
    }
}