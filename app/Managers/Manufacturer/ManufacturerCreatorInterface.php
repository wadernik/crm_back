<?php

declare(strict_types=1);

namespace App\Managers\Manufacturer;

use App\DTOs\Manufacturer\CreateManufacturerDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface ManufacturerCreatorInterface
{
    public function create(CreateManufacturerDTOInterface $manufacturerDTO): Model;
}