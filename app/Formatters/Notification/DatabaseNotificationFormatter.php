<?php

declare(strict_types=1);

namespace App\Formatters\Notification;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;

final class DatabaseNotificationFormatter implements DatabaseNotificationFormatterInterface
{
    public function format(DatabaseNotification $notification): array
    {
        $data = $notification->data;

        $message = $data['message'] ?? null;

        unset($data['message']);

        return [
            'id' => $notification->id,
            'message' => $message,
            'read' => $notification->read(),
            'created_at' => Carbon::parse($notification->created_at)->format('Y-m-d H:i:s'),
            'data' => $data,
        ];
    }

    public function formatCollection(DatabaseNotificationCollection $notifications): array
    {
        return $notifications
            ->map(function (DatabaseNotification $notification) {
                return $this->format($notification);
            })
            ->toArray();
    }
}