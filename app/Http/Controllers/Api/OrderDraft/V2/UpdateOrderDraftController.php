<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderDraft\V2;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\UpdateOrderDraftRequest;
use App\Http\Responses\ApiResponse;
use App\Processor\Order\OrderDraftUpdaterProcessorInterface;
use App\Repositories\Order\OrderDraftRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('orders.edit')]
final class UpdateOrderDraftController extends AbstractApiController
{
    public function __invoke(
        int $id,
        UpdateOrderDraftRequest $request,
        OrderDraftRepositoryInterface $orderRepository,
        OrderDraftUpdaterProcessorInterface $updater
    ): JsonResponse
    {
        if (!$order = $orderRepository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $order = $updater->process($order, $request->validated());

        return ApiResponse::responseSuccess($order->toArray());
    }
}