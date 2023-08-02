<?php

declare(strict_types=1);

namespace App\Services\Order\Status;

interface OrderStatusesRetrieverInterface
{
    public function get(): array;
}