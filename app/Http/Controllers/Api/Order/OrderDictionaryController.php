<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Http\Responses\ApiResponse;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class OrderDictionaryController
{
    public function statuses(OrderRepositoryInterface $repository): JsonResponse
    {
        return ApiResponse::responseSuccess($repository->statuses());
    }
}