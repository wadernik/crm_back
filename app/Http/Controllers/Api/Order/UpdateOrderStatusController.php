<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\DTOs\Order\UpdateOrderDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\UpdateOrderStatusRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Order\OrderManagerInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UpdateOrderStatusController extends AbstractApiController
{
    public function updateStatus(UpdateOrderStatusRequest $request, OrderManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('orders.process')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $orderDTO = new UpdateOrderDTO($request->validated());

        if (!$order = $manager->update($orderDTO->orderId(), $orderDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }
}