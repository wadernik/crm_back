<?php

declare(strict_types=1);

namespace App\Repositories\OrderSetting;

use App\Models\OrderSetting\OrderSetting;

interface FindOneByIdInterface
{
    public function find(int $id): ?OrderSetting;
}