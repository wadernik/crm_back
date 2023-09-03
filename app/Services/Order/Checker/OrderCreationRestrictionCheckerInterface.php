<?php

declare(strict_types=1);

namespace App\Services\Order\Checker;

use App\Models\Manufacturer\ManufacturerDateLimit;

interface OrderCreationRestrictionCheckerInterface
{
    public function check(
        ?int $manufacturerId = null,
        ?string $orderDate = null,
        int $limitType = ManufacturerDateLimit::STATUS_FULL_STOP
    ): bool;
}