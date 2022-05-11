<?php

namespace App\Services\Manufacturers;

use App\ModelModifiers\ModelFilters\ManufacturersFilter;
use App\ModelModifiers\ModelSorts\ManufacturersSort;
use App\Models\Manufacturer;
use App\Services\AbstractBaseCollectionService;

class ManufacturersCollectionService extends AbstractBaseCollectionService
{
    public function __construct(Manufacturer $manufacturer, ManufacturersFilter $filter, ManufacturersSort $sort)
    {
        $this->modelClass = $manufacturer;
        $this->modelFilter = $filter;
        $this->modelSort = $sort;
    }
}
