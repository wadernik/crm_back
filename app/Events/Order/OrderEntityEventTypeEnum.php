<?php

declare(strict_types=1);

namespace App\Events\Order;

enum OrderEntityEventTypeEnum: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
}