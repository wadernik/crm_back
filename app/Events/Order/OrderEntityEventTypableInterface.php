<?php

declare(strict_types=1);

namespace App\Events\Order;

interface OrderEntityEventTypableInterface
{
    public function eventType(): OrderEntityEventTypeEnum;
}