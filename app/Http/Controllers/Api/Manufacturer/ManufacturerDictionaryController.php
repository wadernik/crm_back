<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Manufacturer;

use App\Http\Requests\Dictionaries\ManufacturerDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Manufacturer\ManufacturerRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ManufacturerDictionaryController
{
    public function all(
        ManufacturerDictionaryRequest $request,
        ManufacturerRepositoryInterface $repository
    ): JsonResponse
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