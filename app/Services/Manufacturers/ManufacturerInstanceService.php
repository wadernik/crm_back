<?php

namespace App\Services\Manufacturers;

use App\Models\Manufacturer;
use App\Services\AbstractBaseInstanceService;

class ManufacturerInstanceService extends AbstractBaseInstanceService
{
    public function __construct(Manufacturer $manufacturer)
    {
        $this->modelClass = $manufacturer;
    }
}
