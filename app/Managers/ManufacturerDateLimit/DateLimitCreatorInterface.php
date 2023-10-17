<?php

declare(strict_types=1);

namespace App\Managers\ManufacturerDateLimit;

use App\DTOs\ManufacturerDateLimit\CreateDateLimitDTOInterface;
use App\Models\Manufacturer\ManufacturerDateLimit;

interface DateLimitCreatorInterface
{
    public function create(CreateDateLimitDTOInterface $dateLimitDTO): ManufacturerDateLimit;
}