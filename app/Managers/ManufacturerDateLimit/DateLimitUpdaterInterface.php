<?php

declare(strict_types=1);

namespace App\Managers\ManufacturerDateLimit;

use App\DTOs\ManufacturerDateLimit\UpdateDateLimitDTOInterface;
use App\Models\Manufacturer\ManufacturerDateLimit;

interface DateLimitUpdaterInterface
{
    public function update(
        ManufacturerDateLimit $dateLimit,
        UpdateDateLimitDTOInterface $dateLimitDTO
    ): ManufacturerDateLimit;
}