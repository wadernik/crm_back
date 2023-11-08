<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Requests\Orders\ListOrderRequest;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Order\Normal\OrderManagerInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\Order\ManagerExtension\Normal\OrderCreatorServiceInterface;
use App\Services\Order\ManagerExtension\Normal\OrderUpdaterServiceInterface;
use App\Services\Order\OrderExtendAllWithTotalCommentsServiceInterface;
use App\Services\Order\OrderFindWithCommentServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use function array_merge;

final class OrderController extends AbstractApiController
{
    public function index(
        ListOrderRequest $request,
        OrderRepositoryInterface $repository,
        OrderExtendAllWithTotalCommentsServiceInterface $orderExtender
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $requestData = $request->validated();

        $sort = [
            'sort' => $requestData['sort'] ?? null,
            'order' => $requestData['order'] ?? null,
        ];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $repository->applyWith(
            [
                'items',
                'items.files:id,filename',
            ]
        );

        $items = $repository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);
        $total = $repository->count($requestData);

        $items = $orderExtender->extendAllWithTotalComments($items);

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

        return ApiResponse::responseSuccess(
            array_merge($order->toArray(), $order->load('items')->toArray())
        );
    }

    public function update(
        int $id,
        UpdateOrderRequest $request,
        OrderRepositoryInterface $orderRepository,
        OrderUpdaterServiceInterface $service
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $orderRepository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (!$order = $service->update($order, $request->validated())) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess(
            array_merge($order->toArray(), $order->load('items')->toArray())
        );
    }

    public function destroy(
        int $id,
        OrderRepositoryInterface $orderRepository,
        OrderManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.delete')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $orderRepository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (!$order = $manager->delete($order)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }
}