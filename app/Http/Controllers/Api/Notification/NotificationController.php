<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Notification;

use App\Formatters\Notification\DatabaseNotificationFormatterInterface;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Notification\ListNotificationRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class NotificationController extends AbstractApiController
{
    public function list(
        ListNotificationRequest $request,
        NotificationRepositoryInterface $notificationRepository,
        DatabaseNotificationFormatterInterface $notificationFormatter
    ): JsonResponse
    {
        $requestData = $request->validated();

        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $notifications = $notificationFormatter->formatCollection(
            $notificationRepository->findAllByUserWithLimitAndOffset($this->user(), $limit, $offset)
        );

        $total = $notificationRepository->countByUser($this->user());

        return ApiResponse::responseSuccess(data: $notifications, total: $total);
    }

    public function listUnread(
        ListNotificationRequest $request,
        NotificationRepositoryInterface $notificationRepository,
        DatabaseNotificationFormatterInterface $notificationFormatter
    ): JsonResponse
    {
        $requestData = $request->validated();

        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $notifications = $notificationFormatter->formatCollection(
            $notificationRepository->findAllUnreadByUserWithLimitAndOffset($this->user(), $limit, $offset)
        );

        $total = $notificationRepository->countByUser($this->user());

        return ApiResponse::responseSuccess(data: $notifications, total: $total);
    }
}