<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ManufacturersDateLimits\CreateManufacturerDateLimitsRequest;
use App\Http\Requests\ManufacturersDateLimits\ListManufacturerDateLimitsRequest;
use App\Http\Requests\ManufacturersDateLimits\UpdateManufacturerDateLimitsRequest;
use App\Services\ManufacturersDateLimits\ManufacturerDateLimitsCollectionService;
use App\Services\ManufacturersDateLimits\ManufacturerDateLimitsInstanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ManufacturerDateLimitsController extends AbstractBaseApiController
{

    /**
     * @param ManufacturerDateLimitsCollectionService $manufacturerDateLimitsCollectionService
     * @return JsonResponse
     */
    public function limitTypes(
        ManufacturerDateLimitsCollectionService $manufacturerDateLimitsCollectionService
    ): JsonResponse {
        $statuses = $manufacturerDateLimitsCollectionService->getLimitTypes();

        return $this->responseSuccess(data: $statuses, headers: ['x-total-count' => count($statuses)]);
    }

    /**
     * @param ListManufacturerDateLimitsRequest $request
     * @param ManufacturerDateLimitsCollectionService $manufacturerDateLimitsCollectionService
     * @return JsonResponse
     */
    public function index(
        ListManufacturerDateLimitsRequest $request,
        ManufacturerDateLimitsCollectionService $manufacturerDateLimitsCollectionService
    ): JsonResponse {
        if (!$this->isAllowed('orders.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();

            $dateLimits = $manufacturerDateLimitsCollectionService->getInstances(requestParams: $validated);
            $total = $manufacturerDateLimitsCollectionService->countInstances($validated);

            return $this->responseSuccess(data: $dateLimits, headers: ['x-total-count' => $total]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param CreateManufacturerDateLimitsRequest $request
     * @param ManufacturerDateLimitsInstanceService $manufacturerDateLimitsInstanceService
     * @return JsonResponse
     */
    public function store(
        CreateManufacturerDateLimitsRequest $request,
        ManufacturerDateLimitsInstanceService $manufacturerDateLimitsInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('orders.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();

            if (isset($validated['date'])) {
                $manufacturerDateLimitsInstanceService->createInstance($validated);
            } elseif (isset($validated['dates'])) {
                $dates = $validated['dates'];
                unset($validated['dates']);

                foreach ($dates as $date) {
                    $validated['date'] = $date;
                    $manufacturerDateLimitsInstanceService->createInstance($validated);
                }
            }

            return $this->responseSuccess(code: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $id
     * @param UpdateManufacturerDateLimitsRequest $request
     * @param ManufacturerDateLimitsInstanceService $manufacturerDateLimitsInstanceService
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateManufacturerDateLimitsRequest $request,
        ManufacturerDateLimitsInstanceService $manufacturerDateLimitsInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('orders.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();

            if (!$dateLimit = $manufacturerDateLimitsInstanceService->editInstance($id, $validated)) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess(data: $dateLimit->toArray());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $id
     * @param ManufacturerDateLimitsInstanceService $manufacturerDateLimitsInstanceService
     * @return JsonResponse
     */
    public function destroy(
        int $id,
        ManufacturerDateLimitsInstanceService $manufacturerDateLimitsInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('orders.delete')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$manufacturerDateLimitsInstanceService->deleteInstance($id)) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
