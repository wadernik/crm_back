<?php

declare(strict_types=1);

namespace App\Models\Setting;

use function __;

enum SettingTypeEnum: string
{
    case ORDER__STATUS_TIMEOUT = 'status_timeout';
    CASE NOTIFICATION__UNREAD_TIMEOUT = 'notification_unread_timeout';
    CASE NOTIFICATION__READ_TIMEOUT = 'notification_read_timeout';

    public static function idsByEnum(): array
    {
        return [
            self::ORDER__STATUS_TIMEOUT->value => 1,
            self::NOTIFICATION__UNREAD_TIMEOUT->value => 2,
            self::NOTIFICATION__READ_TIMEOUT->value => 3,
        ];
    }

    public static function id(string $type): int
    {
        return match ($type) {
            self::ORDER__STATUS_TIMEOUT->value => 1,
            self::NOTIFICATION__UNREAD_TIMEOUT->value => 2,
            self::NOTIFICATION__READ_TIMEOUT->value => 3,
            default => 0,
        };
    }

    public static function asArray(): array
    {
        return [
            [
                'id' => 1,
                'type' => self::ORDER__STATUS_TIMEOUT->value,
                'name' => __('settings.order.status_timeout'),
            ],
            [
                'id' => 2,
                'type' => self::NOTIFICATION__UNREAD_TIMEOUT->value,
                'name' => __('settings.notification.unread_timeout'),
            ],
            [
                'id' => 3,
                'type' => self::NOTIFICATION__READ_TIMEOUT->value,
                'name' => __('settings.notification.read_timeout'),
            ],
        ];
    }
}