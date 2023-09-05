<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Notification;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class NotificationMarkAsReadController extends AbstractApiController
{
    public function __invoke(string $id, NotificationRepositoryInterface $notificationRepository): JsonResponse
    {
        $notification = $notificationRepository->findOneByUserAndId($this->user(), $id);

        if (!$notification) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $notification->markAsRead();

        return ApiResponse::responseSuccess();
    }
}