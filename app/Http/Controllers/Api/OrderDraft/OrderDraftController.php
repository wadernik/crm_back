<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderDraft;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\CreateOrderDraftRequest;
use App\Http\Requests\Orders\ListOrderRequest;
use App\Http\Requests\Orders\UpdateOrderDraftRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Repositories\Order\OrderDraftRepositoryInterface;
use App\Services\Order\ManagerExtension\Draft\OrderDraftCreatorServiceInterface;
use App\Services\Order\ManagerExtension\Draft\OrderDraftUpdaterServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use function array_merge;

final class OrderDraftController extends AbstractApiController
{
    public function index(ListOrderRequest $request, OrderDraftRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $requestData = $request->validated();

        $requestData['filter']['user_id'] = (string) $this->userId();

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
                'user',
            ]
        );

        $items = $repository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);
        $total = $repository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }

    public function show(int $id, OrderDraftRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $repository->applyWith(
            [
                'items',
                'items.files:id,filename',
            ]
        );

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

        return ApiResponse::responseSuccess(
            array_merge($order->toArray(), ['items' => $order->load('items')->toArray()])
        );
    }

    public function update(
        int $id,
        UpdateOrderDraftRequest $request,
        OrderDraftRepositoryInterface $orderDraftRepository,
        OrderDraftUpdaterServiceInterface $service
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $orderDraftRepository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (!$order = $service->update($order, $request->validated())) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess(
            array_merge($order->toArray(), ['items' => $order->load('items')->toArray()])
        );
    }

    public function destroy(
        int $id,
        OrderDraftRepositoryInterface $orderDraftRepository,
        OrderDraftManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.delete')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$order = $orderDraftRepository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        if (!$order = $manager->delete($order)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }
}