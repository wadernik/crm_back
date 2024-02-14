<?php

namespace App\Notifications;

use App\Events\Order\OrderEntityEventTypeEnum;
use App\Models\Order\Order;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use function __;

class OrderEntityNotification extends Notification implements ShouldBroadcast
{
    public function __construct(private readonly Order $order, private readonly OrderEntityEventTypeEnum $eventType)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [
            'broadcast',
            'database',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => __(
                "notification.order.entity.{$this->eventType->value}",
                ['number' => "#{$this->order->number}"]
            ),
            'data' => [
                'order_id' => $this->order->id,
            ],
        ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => __(
                "notification.order.entity.{$this->eventType->value}",
                ['number' => "#{$this->order->number}"]
            ),
            'data' => [
                'order_id' => $this->order->id,
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return self::class;
    }
}