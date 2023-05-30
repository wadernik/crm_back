<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\ManufacturerDateLimit;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class DateLimitDictionaryController extends AbstractApiController
{
    public function limitTypes(DateLimitRepositoryInterface $repository): JsonResponse
    {
        return ApiResponse::responseSuccess($repository->limitTypes());
    }
}