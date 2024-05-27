<?php

declare(strict_types=1);

namespace App\Managers\OrderComposite;

use App\Models\Order\Order;

interface OrderCompositeDeleterInterface
{
    public function delete(Order $order): void;
}