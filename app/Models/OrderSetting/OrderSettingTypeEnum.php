<?php

declare(strict_types=1);

namespace App\Models\OrderSetting;

use function __;

enum OrderSettingTypeEnum: string
{
    case STATUS_TIMEOUT = 'status_timeout';

    public static function captions(): array
    {
        return [
            self::STATUS_TIMEOUT->value => __('order.settings.status_timeout'),
        ];
    }
}