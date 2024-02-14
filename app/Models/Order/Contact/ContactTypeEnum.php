<?php

declare(strict_types=1);

namespace App\Models\Order\Contact;

use function __;

enum ContactTypeEnum: string
{
    case SOCIAL = 'social';
    case PHONE = 'phone';

    public static function captions(): array
    {
        return [
            self::SOCIAL->value => __('order.contact.social'),
            self::PHONE->value => __('order.contact.phone'),
        ];
    }
}