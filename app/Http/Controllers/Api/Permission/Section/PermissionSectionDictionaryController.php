<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Permission\Section;

use App\Http\Requests\Dictionaries\PermissionSectionDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Permission\Section\PermissionSectionRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class PermissionSectionDictionaryController
{
    public function __invoke(
        PermissionSectionDictionaryRequest $request,
        PermissionSectionRepositoryInterface $repository
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