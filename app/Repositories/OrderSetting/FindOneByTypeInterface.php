<?php

declare(strict_types=1);

namespace App\Repositories\OrderSetting;

use App\Models\OrderSetting\OrderSetting;
use App\Models\OrderSetting\OrderSettingTypeEnum;

interface FindOneByTypeInterface
{
    public function findOneByType(OrderSettingTypeEnum $type): ?OrderSetting;
}