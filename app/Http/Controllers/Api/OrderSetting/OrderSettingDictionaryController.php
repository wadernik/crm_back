<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderSetting;

use App\Http\Responses\ApiResponse;
use App\Services\OrderSetting\OrderSettingTypeRetrieverInterface;
use Illuminate\Http\JsonResponse;
use function count;

final class OrderSettingDictionaryController
{
    public function __invoke(OrderSettingTypeRetrieverInterface $retriever): JsonResponse
    {
        $items = $retriever->get();
        $total = count($items);

        return ApiResponse::responseSuccess(data: $items, total: $total);
    }
}