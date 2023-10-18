<?php

declare(strict_types=1);

namespace App\Managers\Seller;

use App\DTOs\Seller\UpdateSellerDTOInterface;
use App\Models\Seller\Seller;

interface SellerUpdaterInterface
{
    public function update(Seller $seller, UpdateSellerDTOInterface $sellerDTO): Seller;
}