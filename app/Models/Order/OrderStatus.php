<?php

declare(strict_types=1);

namespace App\Models\Order;

use function __;

final class OrderStatus
{
    public const STATUS_ACCEPTED = 1;
    public const STATUS_TAKEN = 2; // Взят на исполнение
    public const STATUS_DELIVERY = 3;
    public const STATUS_SOLD = 4;
    public const STATUS_CANCELED = 5;
    public const STATUS_PRINTED = 6;
    public const STATUS_TO_REPRINT = 7;

    public static function statusCaptions(): array
    {
        return [
            self::STATUS_ACCEPTED => __('order.status.accepted'),
            self::STATUS_TAKEN => __('order.status.taken'),
            self::STATUS_DELIVERY => __('order.status.delivery'),
            self::STATUS_SOLD => __('order.status.sold'),
            self::STATUS_CANCELED => __('order.status.canceled'),
            self::STATUS_PRINTED => __('order.status.printed'),
            self::STATUS_TO_REPRINT => __('order.status.to_reprint'),
        ];
    }
}