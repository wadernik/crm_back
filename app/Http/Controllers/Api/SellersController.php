<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Dictionaries\SellersDictionaryRequest;
use App\Http\Requests\Sellers\CreateSellerRequest;
use App\Http\Requests\Sellers\UpdateSellerRequest;
use App\Http\Requests\Sellers\ListSellersRequest;
use App\Services\Sellers\SellerInstanceService;
use App\Services\Sellers\SellersCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SellersController extends BaseApiController
{
    /**
     * Dictionary
     * @param SellersDictionaryRequest $request
     * @param SellersCollectionService $sellersCollectionService
     * @return JsonResponse
     */
    public function all(
        SellersDictionaryRequest $request,
        SellersCollectionService $sellersCollectionService
    ): JsonResponse {
        try {
            $validated = array_merge($request->validated(), ['sort' => 'id', 'order' => 'asc']);
            $sellers = $sellersCollectionService->getInstances(requestParams: $validated);
            $total = $sellersCollectionService->countInstances($validated);

            return $this->responseSuccess(data: $sellers, headers: ['x-total-count' => $total]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param ListSellersRequest $request
     * @param SellersCollectionService $sellersCollectionService
     * @return JsonResponse
     */
    public function index(ListSellersRequest $request, SellersCollectionService $sellersCollectionService): JsonResponse
    {
        if (!$this->isAllowed('sellers.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $validated = $request->validated();
            $sellers = $sellersCollectionService->getInstances(requestParams: $validated);
            $total = $sellersCollectionService->countInstances($validated);

            return $this->responseSuccess(data: $sellers, headers: ['x-total-count' => $total]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param SellerInstanceService $sellerInstanceService
     * @return JsonResponse
     */
    public function show(int $id, SellerInstanceService $sellerInstanceService): JsonResponse
    {
        if (!$this->isAllowed('sellers.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$seller = $sellerInstanceService->getInstance($id)) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess(data: $seller);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param CreateSellerRequest $request
     * @param SellerInstanceService $sellerInstanceService
     * @return JsonResponse
     */
    public function store(CreateSellerRequest $request, SellerInstanceService $sellerInstanceService): JsonResponse
    {
        if (!$this->isAllowed('sellers.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $seller = $sellerInstanceService->createInstance($request->validated());

            return $this->responseSuccess(data: ['id' => $seller['id']], code: Response::HTTP_CREATED);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param UpdateSellerRequest $request
     * @param SellerInstanceService $sellerInstanceService
     * @return JsonResponse
     */
    public function update(
        int $id,
        UpdateSellerRequest $request,
        SellerInstanceService $sellerInstanceService
    ): JsonResponse {
        if (!$this->isAllowed('sellers.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$sellerInstanceService->editInstance($id, $request->validated())) {
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
     * @param SellerInstanceService $sellerInstanceService
     * @return JsonResponse
     */
    public function destroy(int $id, SellerInstanceService $sellerInstanceService): JsonResponse
    {
        if (!$this->isAllowed('sellers.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$sellerInstanceService->deleteInstance($id)) {
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
