<?php

declare(strict_types=1);

namespace App\Models\Manufacturer;

interface ManufacturerDateLimitInterface
{
    public static function limitTypes(): array;
}