<?php

declare(strict_types=1);

namespace App\Repositories\OrderSetting;

use App\Models\OrderSetting\OrderSetting;

interface FindOneByTypeIdInterface
{
    public function findOneByTypeId(int $typeId): ?OrderSetting;
}