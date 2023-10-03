<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Responses\ApiResponse;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use function count;

final class UserStatusDictionaryController
{
    public function statuses(UserRepositoryInterface $repository): JsonResponse
    {
        return ApiResponse::responseSuccess(data: $repository->statuses(), total: count($repository->statuses()));
    }
}