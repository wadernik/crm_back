<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Manufacturer;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Dictionaries\ManufacturerDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Manufacturer\ManufacturerRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ManufacturerDictionaryController extends AbstractApiController
{
    public function all(
        ManufacturerDictionaryRequest $request,
        ManufacturerRepositoryInterface $repository
    ): JsonResponse
    {
        $criteria = $request->validated();

        $items = $repository->findAllBy($criteria);
        $total = $repository->count($criteria);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }
}