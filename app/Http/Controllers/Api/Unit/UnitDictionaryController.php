<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Unit;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Models\Unit\UnitEnum;
use Illuminate\Http\JsonResponse;
use function count;

final class UnitDictionaryController extends AbstractApiController
{
    public function __invoke(): JsonResponse
    {
        return ApiResponse::responseSuccess(data: UnitEnum::toArray(), total: count(UnitEnum::cases()));
    }
}