<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Processor\Delivery\OrderDeliveryCourierSetterProcessor;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('order.delivery.courier.edit')]
final class OrderDeliverySetCourierController extends AbstractApiController
{
    public function __invoke(
        int $orderId,
        OrderRepositoryInterface $orderRepository,
        OrderDeliveryCourierSetterProcessor $courierSetter
    ): JsonResponse {
        if (!$order = $orderRepository->find($orderId)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $courierSetter->setCourier($order, $this->user());

        return ApiResponse::responseSuccess($order->toArray());
    }
}