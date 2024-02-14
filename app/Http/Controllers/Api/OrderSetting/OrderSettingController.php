<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderSetting;

use App\DTOs\OrderSetting\CreateOrderSettingDTO;
use App\DTOs\OrderSetting\UpdateOrderSettingDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\OrderSetting\CreateOrderSettingRequest;
use App\Http\Requests\OrderSetting\ListOrderSettingRequest;
use App\Http\Requests\OrderSetting\UpdateOrderSettingRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\OrderSetting\OrderSettingManagerInterface;
use App\Repositories\OrderSetting\OrderSettingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class OrderSettingController extends AbstractApiController
{
    public function index(ListOrderSettingRequest $request, OrderSettingRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('orders.settings.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $requestData = $request->validated();

        $sort = [
            'sort' => $requestData['sort'] ?? null,
            'order' => $requestData['order'] ?? null,
        ];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $items = $repository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);
        $total = $repository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }

    public function show(int $id, OrderSettingRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('orders.settings.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$orderSetting = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($orderSetting->toArray());
    }

    public function store(CreateOrderSettingRequest $request, OrderSettingManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('orders.settings.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $dto = new CreateOrderSettingDTO($request->validated());

        if (!$orderSetting = $manager->create($dto)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($orderSetting->toArray());
    }

    public function update(
        int $id,
        UpdateOrderSettingRequest $request,
        OrderSettingRepositoryInterface $repository,
        OrderSettingManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.settings.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $dto = new UpdateOrderSettingDTO($request->validated());

        if (!$orderSetting = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $orderSetting = $manager->update($orderSetting, $dto);

        return ApiResponse::responseSuccess($orderSetting->toArray());
    }

    public function destroy(
        int $id,
        OrderSettingRepositoryInterface $repository,
        OrderSettingManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.settings.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$orderSetting = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $orderSetting = $manager->delete($orderSetting);

        return ApiResponse::responseSuccess($orderSetting->toArray());
    }
}