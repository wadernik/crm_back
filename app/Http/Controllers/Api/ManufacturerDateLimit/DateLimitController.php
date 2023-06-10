<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\ManufacturerDateLimit;

use App\DTOs\ManufacturerDateLimit\CreateDateLimitDTO;
use App\DTOs\ManufacturerDateLimit\UpdateDateLimitDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\ManufacturersDateLimits\CreateManufacturerDateLimitRequest;
use App\Http\Requests\ManufacturersDateLimits\ListManufacturerDateLimitRequest;
use App\Http\Requests\ManufacturersDateLimits\UpdateManufacturerDateLimitRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\ManufacturerDateLimit\DateLimitManagerInterface;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DateLimitController extends AbstractApiController
{
    public function index(
        ListManufacturerDateLimitRequest $request,
        DateLimitRepositoryInterface $repository
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $requestData = $request->validated();

        $sort = ['sort' => $requestData['sort'] ?? null, 'order' => $requestData['order'] ?? null];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $items = $repository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);
        $total = $repository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }

    public function show(int $id, DateLimitRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$dateLimit = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($dateLimit->toArray());
    }

    public function store(CreateManufacturerDateLimitRequest $request, DateLimitManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('orders.stop.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $dateLimitDTO = new CreateDateLimitDTO($request->validated());

        if (!$dateLimit = $manager->create($dateLimitDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($dateLimit->toArray());
    }

    public function update(
        int $id,
        UpdateManufacturerDateLimitRequest $request,
        DateLimitManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('orders.stop.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $dateLimitDTO = new UpdateDateLimitDTO($request->validated());

        if ($dateLimit = $manager->update($id, $dateLimitDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($dateLimit->toArray());
    }

    public function destroy(int $id, DateLimitManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('orders.stop.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$dateLimit = $manager->delete($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($dateLimit->toArray());
    }
}