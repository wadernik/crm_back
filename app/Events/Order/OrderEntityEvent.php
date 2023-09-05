<?php

namespace App\Events\Order;

use App\Models\Order\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderEntityEvent implements OrderableInterface, OrderEntityEventTypableInterface
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(private readonly Order $order, private readonly OrderEntityEventTypeEnum $eventType)
    {
    }

    public function order(): Order
    {
        return $this->order;
    }

    public function eventType(): OrderEntityEventTypeEnum
    {
        return $this->eventType;
    }
}