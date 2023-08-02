<?php

declare(strict_types=1);

namespace App\Services\Order\Processor;

use App\Models\Order\Order;

interface OrderFinalPriceProcessorInterface
{
    public function run(Order $order): int;
}