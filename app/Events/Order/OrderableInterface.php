<?php

declare(strict_types=1);

namespace App\Events\Order;

use App\Models\Order\Order;

interface OrderableInterface
{
    public function order(): Order;
}