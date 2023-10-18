<?php

declare(strict_types=1);

namespace App\Managers\Seller;

use App\DTOs\Seller\CreateSellerDTOInterface;
use App\Models\Seller\Seller;

interface SellerCreatorInterface
{
    public function create(CreateSellerDTOInterface $sellerDTO): Seller;
}