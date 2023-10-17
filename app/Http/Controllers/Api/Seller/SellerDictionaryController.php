<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Seller;

use App\Http\Requests\Dictionaries\SellerDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Seller\SellerRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class SellerDictionaryController
{
    public function __invoke(SellerDictionaryRequest $request, SellerRepositoryInterface $repository): JsonResponse
    {
        $requestData = $request->validated();

        $sort = ['sort' => $requestData['sort'] ?? 'id', 'order' => $requestData['order'] ?? 'asc'];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $items = $repository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);
        $total = $repository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }
}