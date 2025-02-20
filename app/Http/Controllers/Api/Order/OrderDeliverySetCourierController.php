<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\SetCourierToDeliveryRequest;
use App\Http\Responses\ApiResponse;
use App\Processor\Delivery\OrderDeliveryCourierSetterProcessor;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('order.delivery.courier.edit')]
final class OrderDeliverySetCourierController extends AbstractApiController
{
    public function __invoke(
        int $orderId,
        SetCourierToDeliveryRequest $request,
        OrderRepositoryInterface $orderRepository,
        UserRepositoryInterface $userRepository,
        OrderDeliveryCourierSetterProcessor $courierSetter
    ): JsonResponse {
        if (!$order = $orderRepository->find($orderId)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $courierId = $request->validated()['courier_id'] ?? null;

        $courier = $this->user();

        if ($courierId) {
            $courier = $userRepository->find($courierId);
        }

        if (!$courier) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $courierSetter->setCourier($order, $courier);

        return ApiResponse::responseSuccess($order->toArray());
    }
}