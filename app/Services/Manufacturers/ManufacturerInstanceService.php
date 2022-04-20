<?php

namespace App\Services\Manufacturers;

use App\Models\Manufacturer;
use App\Services\BaseInstanceService;

class ManufacturerInstanceService extends BaseInstanceService
{
    public function __construct(Manufacturer $manufacturer)
    {
        $this->modelClass = $manufacturer;
    }
}
