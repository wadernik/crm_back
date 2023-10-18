<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\ListOrderActivityRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Order\Activity\OrderActivityServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class OrderActivityController extends AbstractApiController
{
    public function __invoke(
        int $id,
        ListOrderActivityRequest $request,
        OrderActivityServiceInterface $service
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $criteria = $request->validated();

        [$items, $total] = $service->activities($id, $criteria);

        return ApiResponse::responseSuccess(data: $items, total: $total);
    }
}