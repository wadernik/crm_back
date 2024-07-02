<?php

declare(strict_types=1);

namespace App\Models\Setting;

use function __;

enum SettingValueTypeEnum: string
{
    case HOUR = 'hour';
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';

    public static function idsByEnum(): array
    {
        return [
            self::HOUR->value => 1,
            self::DAY->value => 2,
            self::WEEK->value => 3,
            self::MONTH->value => 4,
        ];
    }

    public static function tryFromId(int $id): ?self
    {
        return match ($id) {
            1 => self::HOUR,
            2 => self::DAY,
            3 => self::WEEK,
            4 => self::MONTH,
            default => null,
        };
    }

    public static function id(string $type): int
    {
        return match($type) {
            self::HOUR->value => 1,
            self::DAY->value => 2,
            self::WEEK->value => 3,
            self::MONTH->value => 4,
            default => 0,
        };
    }

    public static function asArray(): array
    {
        return [
            [
                'id' => 1,
                'type' => self::HOUR->value,
                'name' => __('settings.value_type.hour'),
            ],
            [
                'id' => 2,
                'type' => self::DAY->value,
                'name' => __('settings.value_type.day'),
            ],
            [
                'id' => 3,
                'type' => self::WEEK->value,
                'name' => __('settings.order.week'),
            ],
            [
                'id' => 4,
                'type' => self::MONTH->value,
                'name' => __('settings.order.month'),
            ],
        ];
    }
}