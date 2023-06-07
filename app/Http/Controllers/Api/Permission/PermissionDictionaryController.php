<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Permission;

use App\Http\Requests\Dictionaries\PermissionDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Permission\PermissionRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class PermissionDictionaryController
{
    public function all(PermissionDictionaryRequest $request, PermissionRepositoryInterface $repository): JsonResponse
    {
        $criteria = $request->validated();

        $items = $repository->findAllBy($criteria);
        $total = $repository->count($criteria);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }
}