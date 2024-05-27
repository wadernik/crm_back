<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\V2;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Responses\ApiResponse;
use App\Processor\Order\OrderCreatorProcessorInterface;
use Illuminate\Http\JsonResponse;

#[Permission('orders.edit')]
final class CreateOrderController extends AbstractApiController
{
    public function __invoke(CreateOrderRequest $request, OrderCreatorProcessorInterface $creator): JsonResponse
    {
        $order = $creator->process($request->validated());

        return ApiResponse::responseSuccess($order->toArray());
    }
}