<?php

declare(strict_types=1);

namespace App\Processor\Order;

use App\DTOs\Order\Composite\OrderCompositeInterface;

interface OrderCreatorProcessorInterface
{
    public function process(array $request): OrderCompositeInterface;
}