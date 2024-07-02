<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderSetting;

use App\Http\Responses\ApiResponse;
use App\Models\Setting\SettingTypeEnum;
use Illuminate\Http\JsonResponse;
use function count;

final class SettingDictionaryController
{
    public function __invoke(): JsonResponse
    {
        $items = SettingTypeEnum::asArray();
        $total = count($items);

        return ApiResponse::responseSuccess(data: $items, total: $total);
    }
}