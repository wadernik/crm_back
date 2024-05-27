<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderDraft\V2;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\CreateOrderDraftRequest;
use App\Http\Responses\ApiResponse;
use App\Processor\Order\OrderDraftCreatorProcessorInterface;
use Illuminate\Http\JsonResponse;

#[Permission('orders.edit')]
final class CreateOrderDraftController extends AbstractApiController
{
    public function __invoke(CreateOrderDraftRequest $request, OrderDraftCreatorProcessorInterface $creator): JsonResponse
    {
        $order = $creator->process($request->validated());

        return ApiResponse::responseSuccess($order->toArray());
    }
}