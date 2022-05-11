<?php

namespace App\Services\Sellers;

use App\Models\Seller;
use App\Services\AbstractBaseInstanceService;

class SellerInstanceService extends AbstractBaseInstanceService
{
    public function __construct(Seller $seller)
    {
        $this->modelClass = $seller;
    }
}
