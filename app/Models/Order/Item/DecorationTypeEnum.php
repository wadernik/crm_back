<?php

declare(strict_types=1);

namespace App\Models\Order\Item;

use function __;

enum DecorationTypeEnum: string
{
    case SWEETS = 'sweets';
    case CREAM = 'cream';
    case CREAM_MASTIC = 'cream_mastic';
    case MASTIC = 'mastic';

    public static function captions(): array
    {
        return [
            self::SWEETS->value => __('order.item.decoration.sweets'),
            self::CREAM->value => __('order.item.decoration.cream'),
            self::CREAM_MASTIC->value => __('order.item.decoration.cream_mastic'),
            self::MASTIC->value => __('order.item.decoration.mastic'),
        ];
    }

    public static function asArray(): array
    {
        return [
            [
                'id' => 1,
                'name' => self::SWEETS->value,
                'description' => __('order.item.decoration.sweets'),
            ],
            [
                'id' => 2,
                'name' => self::CREAM->value,
                'description' => __('order.item.decoration.cream'),
            ],
            [
                'id' => 3,
                'name' => self::CREAM_MASTIC->value,
                'description' => __('order.item.decoration.cream_mastic'),
            ],
            [
                'id' => 4,
                'name' => self::MASTIC->value,
                'description' => __('order.item.decoration.mastic'),
            ],
        ];
    }
}