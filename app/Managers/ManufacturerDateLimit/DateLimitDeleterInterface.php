<?php

declare(strict_types=1);

namespace App\Managers\ManufacturerDateLimit;

use App\Models\Manufacturer\ManufacturerDateLimit;

interface DateLimitDeleterInterface
{
    public function delete(int $id): ?ManufacturerDateLimit;
}