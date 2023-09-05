<?php

declare(strict_types=1);

namespace App\Repositories\Notification;

use App\Models\User\User;
use Illuminate\Notifications\DatabaseNotification;

interface FindOneByUserAndIdInterface
{
    public function findOneByUserAndId(User $user, string $id): ?DatabaseNotification;
}