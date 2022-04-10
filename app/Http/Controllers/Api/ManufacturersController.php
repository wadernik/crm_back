<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Dictionaries\ManufacturersDictionaryRequest;
use App\Services\ManufacturersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ManufacturersController extends BaseApiController
{
    /**
     * @param ManufacturersDictionaryRequest $request
     * @param ManufacturersService $manufacturersService
     * @return JsonResponse
     */
    public function all(
        ManufacturersDictionaryRequest $request,
        ManufacturersService $manufacturersService
    ): JsonResponse {
        try {
            $manufacturers = $manufacturersService->getManufacturers(requestParams: $request->validated());

            return $this->responseSuccess(data: $manufacturers, headers: ['x-total-count' => count($manufacturers)]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
