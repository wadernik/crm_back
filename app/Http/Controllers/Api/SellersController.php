<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Dictionaries\SellersDictionaryRequest;
use App\Services\SellersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SellersController extends BaseApiController
{
    /**
     * @param SellersDictionaryRequest $request
     * @param SellersService $sellersService
     * @return JsonResponse
     */
    public function all(SellersDictionaryRequest $request, SellersService $sellersService): JsonResponse
    {
        try {
            $sellers = $sellersService->getSellers(requestParams: $request->validated());
            return $this->responseSuccess(data: $sellers, headers: ['x-total-count' => count($sellers)]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
