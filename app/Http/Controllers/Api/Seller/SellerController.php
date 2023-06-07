<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Seller;

use App\DTOs\Seller\CreateSellerDTO;
use App\DTOs\Seller\UpdateSellerDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Sellers\CreateSellerRequest;
use App\Http\Requests\Sellers\ListSellersRequest;
use App\Http\Requests\Sellers\UpdateSellerRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Seller\SellerManagerInterface;
use App\Repositories\Seller\SellerRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SellerController extends AbstractApiController
{
    public function index(ListSellersRequest $request, SellerRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('sellers.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $requestData = $request->validated();

        $sort = ['sort' => $requestData['sort'] ?? null, 'order' => $requestData['order'] ?? null];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['offset'] ?? null;

        $items = $repository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);
        $total = $repository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }

    public function show(int $id, SellerRepositoryInterface $repository): JsonResponse
    {
        if (!$this->isAllowed('sellers.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$seller = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($seller->toArray());
    }

    public function store(CreateSellerRequest $request, SellerManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('sellers.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $sellerDTO = new CreateSellerDTO($request->validated());

        if (!$seller = $manager->create($sellerDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($seller->toArray());
    }

    public function update(int $id, UpdateSellerRequest $request, SellerManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('sellers.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $sellerDTO = new UpdateSellerDTO($request->validated());

        if (!$seller = $manager->update($id, $sellerDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($seller->toArray());
    }

    public function destroy(int $id, SellerManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('sellers.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$seller = $manager->delete($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($seller->toArray());
    }
}