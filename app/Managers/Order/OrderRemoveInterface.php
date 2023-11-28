<?php

declare(strict_types=1);

namespace App\Managers\Order;

use App\Models\Order\Order;

interface OrderRemoveInterface
{
    public function remove(Order $order): Order;
}