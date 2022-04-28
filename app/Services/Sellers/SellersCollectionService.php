<?php

namespace App\Services\Sellers;

use App\ModelModifiers\ModelFilters\SellersFilter;
use App\ModelModifiers\ModelSorts\SellersSort;
use App\Models\Seller;
use App\Services\BaseCollectionService;

class SellersCollectionService extends BaseCollectionService
{
    public function __construct(Seller $seller, SellersFilter $filter, SellersSort $sort)
    {
        $this->modelClass = $seller;
        $this->modelFilter = $filter;
        $this->modelSort = $sort;
    }
}
