<?php

declare(strict_types=1);

namespace App\Models\Order;

use function __;

final class OrderStatus
{
    public const STATUS_CREATED = 1;
    public const STATUS_TAKEN = 2; // Взят на исполнение
    public const STATUS_DELIVERY = 3;
    public const STATUS_SOLD = 4;
    public const STATUS_CANCELED = 5;
    public const STATUS_PRINTED = 6;
    public const STATUS_TO_REPRINT = 7;
    public const STATUS_PENDING_ON_AGREEMENT = 8; // На согласовании

    public static function statusCaptions(): array
    {
        return [
            self::STATUS_CREATED => __('order.status.created'),
            self::STATUS_TAKEN => __('order.status.taken'),
            self::STATUS_DELIVERY => __('order.status.delivery'),
            self::STATUS_SOLD => __('order.status.sold'),
            self::STATUS_CANCELED => __('order.status.canceled'),
            self::STATUS_PRINTED => __('order.status.printed'),
            self::STATUS_TO_REPRINT => __('order.status.to_reprint'),
            self::STATUS_PENDING_ON_AGREEMENT => __('order.status.pending_on_agreement'),
        ];
    }

    public static function position(int $status): int
    {
        $positions = [
            self::STATUS_CREATED => 1,
            self::STATUS_TAKEN => 3,
            self::STATUS_DELIVERY => 4,
            self::STATUS_SOLD => 5,
            self::STATUS_CANCELED => 6,
            self::STATUS_PRINTED => 7,
            self::STATUS_TO_REPRINT => 8,
            self::STATUS_PENDING_ON_AGREEMENT => 2,
        ];

        return $positions[$status] ?? 0;
    }
}