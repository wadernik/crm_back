<?php

namespace App\Services\Manufacturers;

use App\ModelModifiers\ModelFilters\ManufacturersFilter;
use App\ModelModifiers\ModelSorts\ManufacturersSort;
use App\Models\Manufacturer;
use App\Services\BaseCollectionService;

class ManufacturersCollectionService extends BaseCollectionService
{
    public function __construct(Manufacturer $manufacturer, ManufacturersFilter $filter, ManufacturersSort $sort)
    {
        $this->modelClass = $manufacturer;
        $this->modelFilter = $filter;
        $this->modelSort = $sort;
    }
}
