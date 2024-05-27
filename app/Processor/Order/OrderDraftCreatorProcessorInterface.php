<?php

declare(strict_types=1);

namespace App\Processor\Order;

use App\DTOs\Order\Composite\OrderCompositeInterface;

interface OrderDraftCreatorProcessorInterface
{
    public function process(array $request): OrderCompositeInterface;
}