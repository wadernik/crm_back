<?php

declare(strict_types=1);

namespace App\Formatters\Notification;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;

interface DatabaseNotificationFormatterInterface
{
    /**
     * @param DatabaseNotification $notification
     *
     * @return array{
     *     id: string,
     *     message: string,
     *     read: bool,
     *     created_at: string,
     *     data: array
     * }
     */
    public function format(DatabaseNotification $notification): array;

    /**
     * @param DatabaseNotificationCollection $notifications
     *
     * @return array<array{
     *     id: string,
     *     message: string,
     *     read: bool,
     *     created_at: string,
     *     data: array
     * }>
     */
    public function formatCollection(DatabaseNotificationCollection $notifications): array;
}