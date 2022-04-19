<?php

namespace App\Services\Sellers;

use App\ModelFilters\SellersFilter;
use App\Models\Seller;
use App\Services\BaseCollectionService;

class SellersCollectionService extends BaseCollectionService
{
    public function __construct(Seller $seller, SellersFilter $filter)
    {
        $this->modelClass = $seller;
        $this->modelFilter = $filter;
    }
}
