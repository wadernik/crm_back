<?php

namespace App\Services\Sellers;

use App\Models\Seller;
use App\Services\BaseInstanceService;

class SellerInstanceService extends BaseInstanceService
{
    public function __construct(Seller $seller)
    {
        $this->modelClass = $seller;
    }
}
