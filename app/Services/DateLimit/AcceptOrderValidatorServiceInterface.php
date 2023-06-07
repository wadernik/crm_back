<?php

declare(strict_types=1);

namespace App\Services\DateLimit;

use App\Models\Manufacturer\ManufacturerDateLimit;

interface AcceptOrderValidatorServiceInterface
{
    public function canAccept(
        int $manufacturerId,
        string $orderDate,
        int $limitType = ManufacturerDateLimit::STATUS_FULL_STOP
    ): bool;
}