<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderDraft;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\CreateOrderDraftRequest;
use App\Http\Requests\Orders\ListOrderRequest;
use App\Http\Requests\Orders\UpdateOrderDraftRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Repositories\Order\Draft\OrderDraftRepositoryInterface;
use App\Services\Order\Draft\OrderDraftCreatorServiceInterface;
use App\Services\Order\Draft\OrderDraftUpdaterServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class OrderDraftController extends AbstractApiController
{
    public function index(ListOrderRequest $request, OrderDraftRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $requestData = $request->validated();

        $requestData['filter']['user_id'] = (string) auth('sanctum')->id();

        $sort = ['sort' => $requestData['sort'] ?? null, 'order' => $requestData['order'] ?? null];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $attributes = [
            'orders.*',
            'order_details.id AS order_detail_id',
            'order_details.name',
            'order_details.amount',
            'order_details.label',
            'order_details.decoration',
            'order_details.comment',
        ];

        $repository->join('users', 'users.id', '=', 'orders.user_id');
        $repository->applyWith(['files:id,filename']);

        $items = $repository->findAllBy($requestData, $attributes, $sort, $limit, $offset);
        $total = $repository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }

    public function show(int $id, OrderDraftRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $repository->applyWith(['files:id,filename']);

        if (!$order = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }

    public function store(CreateOrderDraftRequest $request, OrderDraftCreatorServiceInterface $service): JsonResponse
    {
        if (!$this->isAllowed('orders.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $service->create($request->validated())) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }

    public function update(
        int $id, UpdateOrderDraftRequest $request,
        OrderDraftUpdaterServiceInterface $service
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $service->update($id, $request->validated())) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }

    public function destroy(int $id, OrderDraftManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('orders.delete')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $manager->delete($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }
}