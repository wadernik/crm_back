<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Manufacturer;

use App\DTOs\Manufacturer\CreateManufacturerDTO;
use App\DTOs\Manufacturer\UpdateManufacturerDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Manufacturers\CreateManufacturerRequest;
use App\Http\Requests\Manufacturers\ListManufacturerRequest;
use App\Http\Requests\Manufacturers\UpdateManufacturerRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Manufacturer\ManufacturerManagerInterface;
use App\Repositories\Manufacturer\ManufacturerRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ManufacturerController extends AbstractApiController
{
    public function index(ListManufacturerRequest $request, ManufacturerRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('manufacturers.view')) {
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

    public function show(int $id, ManufacturerRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('manufacturers.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$manufacturer = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($manufacturer->toArray());
    }

    public function store(CreateManufacturerRequest $request, ManufacturerManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('manufacturers.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $manufacturerDTO = new CreateManufacturerDTO($request->validated());

        $manufacturer = $manager->create($manufacturerDTO);

        return ApiResponse::responseSuccess($manufacturer->toArray());
    }

    public function update(
        int $id,
        UpdateManufacturerRequest $request,
        ManufacturerRepositoryInterface $repository,
        ManufacturerManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('manufacturers.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $manufacturerDTO = new UpdateManufacturerDTO($request->validated());

        if (!$manufacturer = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $manufacturer = $manager->update($manufacturer, $manufacturerDTO);

        return ApiResponse::responseSuccess($manufacturer->toArray());
    }

    public function destroy(
        int $id,
        ManufacturerRepositoryInterface $repository,
        ManufacturerManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('manufacturers.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$manufacturer = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $manufacturer = $manager->delete($manufacturer);

        return ApiResponse::responseSuccess($manufacturer->toArray());
    }
}