<?php

declare(strict_types=1);

namespace App\Repositories\Notification;

use App\Models\User\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;

final class NotificationV2Repository implements NotificationV2RepositoryInterface
{
    public function findAllUnreadByUserWithLimitAndOffset(
        User $user,
        ?string $limit = null,
        ?string $offset = null
    ): DatabaseNotificationCollection
    {
        $query = DatabaseNotification::query()
            ->whereMorphedTo('notifiable', User::class)
            ->where('notifiable_id', $user->id);

        if ($limit) {
            $query->limit((int) $limit);
        }

        if ($offset) {
            $query->offset((int) $limit * ((int) $offset - 1));
        }

        return DatabaseNotificationCollection::make($query->get());
    }

    public function findOneByUserAndId(User $user, string $id): ?DatabaseNotification
    {
        /** @var ?DatabaseNotification $notification */
        $notification = DatabaseNotification::query()
            ->whereMorphedTo('notifiable', User::class)
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->find($id);

        return $notification;
    }

    public function countUnreadByUser(User $user): int
    {
        return DatabaseNotification::query()
            ->whereMorphedTo('notifiable', User::class)
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }
}