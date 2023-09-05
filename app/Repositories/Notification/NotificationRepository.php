<?php

declare(strict_types=1);

namespace App\Repositories\Notification;

use App\Models\User\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;

final class NotificationRepository implements NotificationRepositoryInterface
{
    public function findAllByUser(User $user): DatabaseNotificationCollection
    {
        return $user->notifications;
    }

    public function findOneByUserAndId(User $user, string $id): ?DatabaseNotification
    {
        foreach ($this->findAllByUser($user)->all() as $notification) {
            if ($notification->id === $id) {
                return $notification;
            }
        }

        return null;
    }
}