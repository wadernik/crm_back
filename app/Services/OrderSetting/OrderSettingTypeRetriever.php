<?php

declare(strict_types=1);

namespace App\Services\OrderSetting;

use App\Models\OrderSetting\OrderSettingTypeEnum;
use function collect;

final class OrderSettingTypeRetriever implements OrderSettingTypeRetrieverInterface
{
    public function get(): array
    {
        return collect(OrderSettingTypeEnum::captions())
            ->map(function (string $caption, $key) {
                return [
                    'id' => $key,
                    'name' => $key,
                    'description' => $caption,
                ];
            })
            ->values()
            ->toArray();
    }
}