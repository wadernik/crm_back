<?php

namespace App\Services\Manufacturers;

use App\ModelFilters\ManufacturersFilter;
use App\Models\Manufacturer;
use App\Services\BaseCollectionService;

class ManufacturersCollectionService extends BaseCollectionService
{
    public function __construct(Manufacturer $manufacturer, ManufacturersFilter $filter)
    {
        $this->modelClass = $manufacturer;
        $this->modelFilter = $filter;
    }
}
