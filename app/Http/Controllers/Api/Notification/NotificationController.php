<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Notification;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;

final class NotificationController extends AbstractApiController
{
    public function list(): JsonResponse
    {
        $notifications = $this->user()->notifications
            ->map(static function (DatabaseNotification $notification): array {
                return array_merge(['id' => $notification->id, 'read' => $notification->read()], $notification->data);
            })
            ->toArray();

        return ApiResponse::responseSuccess($notifications);
    }

    public function listUnread(): JsonResponse
    {
        $notifications = $this->user()->unreadNotifications
            ->map(static function (DatabaseNotification $notification): array {
                return array_merge(['id' => $notification->id, 'read' => $notification->read()], $notification->data);
            })
            ->toArray();

        return ApiResponse::responseSuccess($notifications);
    }
}