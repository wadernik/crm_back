<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\ManufacturerDateLimit;

use App\Http\Responses\ApiResponse;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;
use Illuminate\Http\JsonResponse;
use function count;

final class DateLimitDictionaryController
{
    public function limitTypes(DateLimitRepositoryInterface $repository): JsonResponse
    {
        $items = $repository->limitTypes();
        $total = count($repository->limitTypes());

        return ApiResponse::responseSuccess(data: $items, total: $total);
    }
}