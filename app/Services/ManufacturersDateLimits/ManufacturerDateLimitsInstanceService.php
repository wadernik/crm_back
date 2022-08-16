<?php

namespace App\Services\ManufacturersDateLimits;

use App\Models\ManufacturerDateLimit;
use App\Services\AbstractBaseInstanceService;

class ManufacturerDateLimitsInstanceService extends AbstractBaseInstanceService
{
    public function __construct(ManufacturerDateLimit $manufacturerDateLimit)
    {
        $this->modelClass = $manufacturerDateLimit;
    }
}
