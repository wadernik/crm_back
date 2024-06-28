<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Notification\V2;

use App\Formatters\Notification\DatabaseNotificationFormatterInterface;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Notification\ListNotificationRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Notification\NotificationV2RepositoryInterface;
use Illuminate\Http\JsonResponse;

final class ListNotificationController extends AbstractApiController
{
    public function __invoke(
        ListNotificationRequest $request,
        NotificationV2RepositoryInterface $notificationRepository,
        DatabaseNotificationFormatterInterface $notificationFormatter
    ): JsonResponse
    {
        $requestData = $request->validated();

        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $notifications = $notificationFormatter->formatCollection(
            $notificationRepository->findAllUnreadByUserWithLimitAndOffset($this->user(), $limit, $offset)
        );

        $total = $notificationRepository->countUnreadByUser($this->user());

        return ApiResponse::responseSuccess(data: $notifications, total: $total);
    }
}