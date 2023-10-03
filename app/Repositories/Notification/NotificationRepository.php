<?php

declare(strict_types=1);

namespace App\Repositories\Notification;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;

final class NotificationRepository implements NotificationRepositoryInterface
{
    public function findAllByUserWithLimitAndOffset(
        User $user,
        ?string $limit = null,
        ?string $offset = null
    ): DatabaseNotificationCollection
    {
        if (!$limit) {
            return $user->notifications;
        }

        return $this->retrieveNotifications($user->notifications(), $limit, $offset);
    }

    public function findAllUnreadByUserWithLimitAndOffset(
        User $user,
        ?string $limit = null,
        ?string $offset = null
    ): DatabaseNotificationCollection
    {
        if (!$limit) {
            return $user->unreadNotifications;
        }

        return $this->retrieveNotifications($user->unreadNotifications(), $limit, $offset);
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

    public function findAllByUser(User $user): DatabaseNotificationCollection
    {
        return $user->notifications;
    }

    public function countByUser(User $user): int
    {
        return $user->notifications()->count();
    }

    /**
     * @param MorphMany|Builder $query
     * @param string|null       $limit
     * @param string|null       $offset
     *
     * @return DatabaseNotificationCollection
     */
    private function retrieveNotifications(
        object $query,
        ?string $limit = null,
        ?string $offset = null
    ): DatabaseNotificationCollection
    {
        $query->limit((int) $limit);

        if ($offset) {
            $query->offset((int) $limit * ((int) $offset - 1));
        }

        /** @var DatabaseNotificationCollection $result */
        $result = $query->get();

        return $result;
    }
}