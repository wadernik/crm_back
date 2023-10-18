<?php

declare(strict_types=1);

namespace App\Managers\Manufacturer;

use App\DTOs\Manufacturer\CreateManufacturerDTOInterface;
use App\Models\Manufacturer\Manufacturer;

interface ManufacturerCreatorInterface
{
    public function create(CreateManufacturerDTOInterface $manufacturerDTO): Manufacturer;
}