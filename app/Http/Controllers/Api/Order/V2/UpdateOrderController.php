<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\V2;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Http\Responses\ApiResponse;
use App\Processor\Order\OrderUpdaterProcessorInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('orders.edit')]
final class UpdateOrderController extends AbstractApiController
{
    public function __invoke(
        int $id,
        UpdateOrderRequest $request,
        OrderRepositoryInterface $orderRepository,
        OrderUpdaterProcessorInterface $updater
    ): JsonResponse
    {
        if (!$order = $orderRepository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $order = $updater->process($order, $request->validated());

        return ApiResponse::responseSuccess($order->toArray());
    }
}