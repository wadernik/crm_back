<?php

declare(strict_types=1);

namespace App\Processor\Order;

use App\DTOs\Order\Composite\OrderCompositeInterface;
use App\Models\Order\Order;

interface OrderUpdaterProcessorInterface
{
    public function process(Order $order, array $request): OrderCompositeInterface;
}