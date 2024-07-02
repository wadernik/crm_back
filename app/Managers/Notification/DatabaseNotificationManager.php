<?php

declare(strict_types=1);

namespace App\Managers\Notification;

use Illuminate\Notifications\DatabaseNotification;

final class DatabaseNotificationManager implements DatabaseNotificationManagerInterface
{
    public function clearReadByDate(string $date): void
    {
        DatabaseNotification::query()
            ->whereNotNull('read_at')
            ->where('read_at', '<=', $date)
            ->delete();
    }

    public function clearUnreadByDate(string $date): void
    {
        DatabaseNotification::query()
            ->whereNull('read_at')
            ->where('created_at', '<=', $date)
            ->delete();
    }
}