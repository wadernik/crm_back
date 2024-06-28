<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Notification\V2;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Repositories\Notification\NotificationV2RepositoryInterface;
use Illuminate\Http\JsonResponse;

final class CountUnreadNotificationController extends AbstractApiController
{
    public function __invoke(NotificationV2RepositoryInterface $repository): JsonResponse
    {
        return ApiResponse::responseSuccess(data: ['total' => $repository->countUnreadByUser($this->user())]);
    }
}