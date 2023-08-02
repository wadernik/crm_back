<?php

declare(strict_types=1);

namespace App\Models\Order;

final class OrderStatus
{
    public const STATUS_ACCEPTED = 1;
    public const STATUS_TAKEN = 2; // Взят на исполнение
    public const STATUS_DELIVERY = 3;
    public const STATUS_SOLD = 4;
    public const STATUS_CANCELED = 5;

    public static function statusCaptions(): array
    {
        return [
            self::STATUS_ACCEPTED => __('order.statuses.accepted'),
            self::STATUS_TAKEN => __('order.statuses.taken'),
            self::STATUS_DELIVERY => __('order.statuses.delivery'),
            self::STATUS_SOLD => __('order.statuses.sold'),
            self::STATUS_CANCELED => __('order.statuses.canceled'),
        ];
    }
}