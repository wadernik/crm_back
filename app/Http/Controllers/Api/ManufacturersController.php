<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Dictionaries\ManufacturersDictionaryRequest;
use App\Http\Requests\Manufacturers\CreateManufacturerRequest;
use App\Http\Requests\Manufacturers\ListManufacturersRequest;
use App\Http\Requests\Manufacturers\UpdateManufacturerRequest;
use App\Services\Manufacturers\ManufacturerInstanceService;
use App\Services\Manufacturers\ManufacturersCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ManufacturersController extends BaseApiController
{
    /**
     * Dictionary
     * @param ManufacturersDictionaryRequest $request
     * @param ManufacturersCollectionService $manufacturersCollectionService
     * @return JsonResponse
     */
    public function all(
        ManufacturersDictionaryRequest $request,
        ManufacturersCollectionService $manufacturersCollectionService
    ): JsonResponse {
        try {
            $validated = array_merge($request->validated(), ['sort' => 'id', 'order' => 'asc']);
            $manufacturers = $manufacturersCollectionService->getInstances(requestParams: $validated);

            return $this->responseSuccess(data: $manufacturers, headers: ['x-total-count' => count($manufacturers)]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param ListManufacturersRequest $request
     * @param ManufacturersCollectionService $manufacturersCollectionService
     * @return JsonResponse
     */
    public function index(
        ListManufacturersRequest $request,
        ManufacturersCollectionService $manufacturersCollectionService
    ): JsonResponse {
        if (!$this->isAllowed('manufacturers.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $manufacturers = $manufacturersCollectionService->getInstances(requestParams: $request->validated());

            return $this->responseSuccess(data: $manufacturers, headers: ['x-total-count' => count($manufacturers)]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param ManufacturerInstanceService $manufacturerInstanceService
     * @return JsonResponse
     */
    public function show(int $id, ManufacturerInstanceService $manufacturerInstanceService): JsonResponse
    {
        if (!$this->isAllowed('manufacturers.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$manufacturer = $manufacturerInstanceService->getInstance($id)) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess(data: $manufacturer, headers: ['x-total-count' => count($manufacturer)]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param CreateManufacturerRequest $request
     * @param ManufacturerInstanceService $manufacturerInstanceService
     * @return JsonResponse
     */
    public function store(
        CreateManufacturerRequest $request,
        ManufacturerInstanceService $manufacturerInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('manufacturers.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $manufacturer = $manufacturerInstanceService->createInstance($request->validated());

            return $this->responseSuccess(data: ['id' => $manufacturer['id']], code: Response::HTTP_CREATED);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param UpdateManufacturerRequest $request
     * @param ManufacturerInstanceService $manufacturerInstanceService
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateManufacturerRequest $request,
        ManufacturerInstanceService $manufacturerInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('manufacturers.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$manufacturerInstanceService->editInstance($id, $request->validated())) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess();
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param ManufacturerInstanceService $manufacturerInstanceService
     * @return JsonResponse
     */
    public function destroy(int $id, ManufacturerInstanceService $manufacturerInstanceService): JsonResponse
    {
        if (!$this->isAllowed('manufacturers.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$manufacturerInstanceService->deleteInstance($id)) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess();
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
