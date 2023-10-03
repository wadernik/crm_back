<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Activity;

use App\Http\Responses\ApiResponse;
use App\Models\Activity\ActivityExtended;
use Illuminate\Http\JsonResponse;
use function count;

final class ActivityDictionaryController
{
    public function subjects(): JsonResponse
    {
        $items = ActivityExtended::getSubjectsList();
        $total = count($items);

        return ApiResponse::responseSuccess(data: $items, total: $total);
    }
}