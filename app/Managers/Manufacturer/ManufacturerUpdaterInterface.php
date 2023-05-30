<?php

declare(strict_types=1);

namespace App\Managers\Manufacturer;

use App\DTOs\Manufacturer\UpdateManufacturerDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface ManufacturerUpdaterInterface
{
    public function update(int $id, UpdateManufacturerDTOInterface $manufacturerDTO): ?Model;
}