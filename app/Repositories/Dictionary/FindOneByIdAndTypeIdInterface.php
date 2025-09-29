<?php

declare(strict_types=1);

namespace App\Repositories\Dictionary;

use App\Models\Dictionary\Dictionary;

interface FindOneByIdAndTypeIdInterface
{
    public function find(int $id, int $typeId): ?Dictionary;
}
