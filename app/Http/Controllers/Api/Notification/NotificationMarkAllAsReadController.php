<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Notification;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

final class NotificationMarkAllAsReadController extends AbstractApiController
{
    public function __invoke(): JsonResponse
    {
        $this->user()->notifications->markAsRead();

        return ApiResponse::responseSuccess();
    }
}