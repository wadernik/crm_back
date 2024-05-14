<?php

declare(strict_types=1);

namespace App\Models\Order;

use function __;

final class OrderStatus
{
    public const STATUS_CREATED = 1;
    public const STATUS_PENDING_ON_AGREEMENT = 2; // На согласовании
    public const STATUS_TAKEN = 3; // Взят на исполнение
    public const STATUS_DELIVERY = 4;
    public const STATUS_SOLD = 5;
    public const STATUS_CANCELED = 6;
    public const STATUS_PRINTED = 7;
    public const STATUS_TO_REPRINT = 8;

    public static function statusCaptions(): array
    {
        return [
            self::STATUS_CREATED => __('order.status.created'),
            self::STATUS_PENDING_ON_AGREEMENT => __('order.status.pending_on_agreement'),
            self::STATUS_TAKEN => __('order.status.taken'),
            self::STATUS_DELIVERY => __('order.status.delivery'),
            self::STATUS_SOLD => __('order.status.sold'),
            self::STATUS_CANCELED => __('order.status.canceled'),
            self::STATUS_PRINTED => __('order.status.printed'),
            self::STATUS_TO_REPRINT => __('order.status.to_reprint'),
        ];
    }
}