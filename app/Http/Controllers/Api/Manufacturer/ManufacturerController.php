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

        $criteria = $request->validated();

        $items = $repository->findAllBy($criteria);
        $total = $repository->count($criteria);

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
        ManufacturerManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('manufacturers.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $manufacturerDTO = new UpdateManufacturerDTO($request->validated());

        if (!$manufacturer = $manager->update($id, $manufacturerDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($manufacturer->toArray());
    }

    public function destroy(int $id, ManufacturerManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('manufacturers.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$manufacturer = $manager->delete($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($manufacturer->toArray());
    }
}