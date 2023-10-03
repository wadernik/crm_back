<?php

declare(strict_types=1);

namespace App\Repositories\Notification;

use App\Models\User\User;
use Illuminate\Notifications\DatabaseNotificationCollection;

interface FindAllByUserWithLimitAndOffsetInterface
{
    public function findAllByUserWithLimitAndOffset(
        User $user,
        ?string $limit = null,
        ?string $offset = null
    ): DatabaseNotificationCollection;
}