<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\ManufacturerDateLimit;

use App\Http\Responses\ApiResponse;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class DateLimitDictionaryController
{
    public function limitTypes(DateLimitRepositoryInterface $repository): JsonResponse
    {
        return ApiResponse::responseSuccess($repository->limitTypes());
    }
}