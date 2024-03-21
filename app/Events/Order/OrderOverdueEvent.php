<?php

declare(strict_types=1);

namespace App\Events\Order;

use App\Models\Order\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class OrderOverdueEvent implements OrderableInterface
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(private readonly Order $order, private readonly int $timeout)
    {
    }

    public function order(): Order
    {
        return $this->order;
    }

    public function timeout(): int
    {
        return $this->timeout;
    }
}