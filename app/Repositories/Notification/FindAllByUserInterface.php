<?php

declare(strict_types=1);

namespace App\Repositories\Notification;

use App\Models\User\User;
use Illuminate\Notifications\DatabaseNotificationCollection;

interface FindAllByUserInterface
{
    /**
     * @param User $user
     *
     * @return DatabaseNotificationCollection
     */
    public function findAllByUser(User $user): DatabaseNotificationCollection;
}