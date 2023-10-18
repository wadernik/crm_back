<?php

declare(strict_types=1);

namespace App\Managers\Manufacturer;

use App\Models\Manufacturer\Manufacturer;

interface ManufacturerDeleterInterface
{
    public function delete(Manufacturer $manufacturer): Manufacturer;
}