<?php

declare(strict_types=1);

namespace App\Repositories\Setting;

use App\Models\Setting\Setting;

interface FindOneByTypeIdInterface
{
    public function findOneByTypeId(int $typeId): ?Setting;
}