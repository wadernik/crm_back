<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Requests\Orders\ListOrderRequest;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Order\OrderManagerInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\Order\OrderCreatorServiceInterface;
use App\Services\Order\OrderFindWithCommentServiceInterface;
use App\Services\Order\OrderUpdaterServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class OrderController extends AbstractApiController
{
    public function index(ListOrderRequest $request, OrderRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $requestData = $request->validated();

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

        $total = $repository->count($requestData);
        $items = $repository->findAllBy($requestData, $attributes, $sort, $limit, $offset);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }

    public function show(int $id, OrderFindWithCommentServiceInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $repository->findWithTotalComments($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }

    public function store(CreateOrderRequest $request, OrderCreatorServiceInterface $service): JsonResponse
    {
        if (!$this->isAllowed('orders.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $service->create($request->validated())) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }

    public function update(int $id, UpdateOrderRequest $request, OrderUpdaterServiceInterface $service): JsonResponse
    {
        if (!$this->isAllowed('orders.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $service->update($id, $request->validated())) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }

    public function destroy(int $id, OrderManagerInterface $manager): JsonResponse
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