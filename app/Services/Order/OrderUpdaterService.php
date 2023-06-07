<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Managers\Order\OrderManagerInterface;

final class OrderUpdaterService extends AbstractOrderUpdaterService
{
    public function __construct()
    {
        parent::__construct(OrderManagerInterface::class);
    }
}