<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\Models\Order\Order;

interface OrderDeleterInterface
{
    public function delete(Order $order): Order;
}