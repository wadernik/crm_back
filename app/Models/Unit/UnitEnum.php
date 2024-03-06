<?php

declare(strict_types=1);

namespace App\Models\Unit;

use function __;

enum UnitEnum: int
{
    case QUANTITY = 1;
    // case WEIGHT_G = 2;
    case WEIGHT_KG = 3;

    public static function captions(): array
    {
        return [
            self::QUANTITY->value => __('unit.caption.quantity'),
            // self::WEIGHT_G->value => __('unit.caption.weight_g'),
            self::WEIGHT_KG->value => __('unit.caption.weight_kg'),
        ];
    }

    public static function shorts(): array
    {
        return [
            self::QUANTITY->value => __('unit.short.quantity'),
            // self::WEIGHT_G->value => __('unit.short.weight_g'),
            self::WEIGHT_KG->value => __('unit.short.weight_kg'),
        ];
    }

    public static function toArray(): array
    {
        return [
            [
                'id' => self::QUANTITY->value,
                'name' => __('unit.caption.quantity'),
                'short' => __('unit.short.quantity'),
            ],
            // [
            //     'id' => self::WEIGHT_G->value,
            //     'name' => __('unit.caption.weight_g'),
            //     'short' => __('unit.short.weight_g'),
            // ],
            [
                'id' => self::WEIGHT_KG->value,
                'name' => __('unit.caption.weight_kg'),
                'short' => __('unit.short.weight_kg'),
            ],
        ];
    }
}