<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderSetting;

use App\DTOs\Setting\CreateSettingDTO;
use App\DTOs\Setting\UpdateSettingDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\OrderSetting\CreateSettingRequest;
use App\Http\Requests\OrderSetting\ListSettingRequest;
use App\Http\Requests\OrderSetting\UpdateSettingRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Setting\SettingManagerInterface;
use App\Repositories\Setting\SettingRepositoryInterface;
use App\Services\Setting\ManagerExtension\SettingCreatorServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class OrderSettingController extends AbstractApiController
{
    public function index(ListSettingRequest $request, SettingRepositoryInterface $repository): JsonResponse
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

    public function show(int $id, SettingRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('orders.settings.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$orderSetting = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($orderSetting->toArray());
    }

    public function store(
        CreateSettingRequest $request,
        SettingCreatorServiceInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.settings.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $dto = new CreateSettingDTO($request->validated());

        if (!$orderSetting = $manager->create($dto)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($orderSetting->toArray());
    }

    public function update(
        int $id,
        UpdateSettingRequest $request,
        SettingRepositoryInterface $repository,
        SettingManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.settings.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $dto = new UpdateSettingDTO($request->validated());

        if (!$orderSetting = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $orderSetting = $manager->update($orderSetting, $dto);

        return ApiResponse::responseSuccess($orderSetting->toArray());
    }

    public function destroy(
        int $id,
        SettingRepositoryInterface $repository,
        SettingManagerInterface $manager
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