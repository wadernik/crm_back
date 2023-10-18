<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Role;

use App\Http\Requests\Dictionaries\RoleDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Role\RoleRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class RoleDictionaryController
{
    public function __invoke(RoleDictionaryRequest $request, RoleRepositoryInterface $repository): JsonResponse
    {
        $repository->applyWith(['permissions']);

        $requestData = $request->validated();

        $sort = ['sort' => $requestData['sort'] ?? 'id', 'order' => $requestData['order'] ?? 'asc'];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $items = $repository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);

        $total = $repository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }
}