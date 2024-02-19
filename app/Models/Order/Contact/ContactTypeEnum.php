<?php

declare(strict_types=1);

namespace App\Models\Order\Contact;

use function __;

enum ContactTypeEnum: string
{
    case SOCIAL = 'social';
    case PHONE = 'phone';

    public static function ids(): array
    {
        return [
            1 => self::SOCIAL->value,
            2 => self::PHONE->value,
        ];
    }

    public static function asArray(): array
    {
        return [
            [
                'id' => 1,
                'type' => self::SOCIAL->value,
                'name' => __('order.contact.social'),
            ],
            [
                'id' => 2,
                'type' => self::PHONE->value,
                'name' => __('order.contact.phone'),
            ],
        ];
    }
}