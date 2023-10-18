<?php

declare(strict_types=1);

namespace App\Repositories\ManufacturerDateLimit;

use App\Models\Manufacturer\ManufacturerDateLimit;

interface FindOneByIdInterface
{
    public function find(int $id): ?ManufacturerDateLimit;
}