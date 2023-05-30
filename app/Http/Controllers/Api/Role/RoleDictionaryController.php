<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Role;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Dictionaries\RoleDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Role\RoleRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class RoleDictionaryController extends AbstractApiController
{
    public function all(RoleDictionaryRequest $request, RoleRepositoryInterface $repository): JsonResponse
    {
        $criteria = $request->validated();

        $items = $repository->findAllBy($criteria);
        $total = $repository->count($criteria);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }
}