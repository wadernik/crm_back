<?php

declare(strict_types=1);

namespace App\Models\OrderSetting;

use function __;

enum OrderSettingTypeEnum: string
{
    case STATUS_TIMEOUT = 'status_timeout';

    public static function idsByEnum(): array
    {
        return [
            self::STATUS_TIMEOUT->value => 1,
        ];
    }

    public static function asArray(): array
    {
        return [
            [
                'id' => 1,
                'type' => self::STATUS_TIMEOUT->value,
                'name' => __('order.settings.status_timeout'),
            ]
        ];
    }
}