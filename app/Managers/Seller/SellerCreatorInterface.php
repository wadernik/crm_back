<?php

declare(strict_types=1);

namespace App\Managers\Seller;

use App\DTOs\Seller\CreateSellerDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface SellerCreatorInterface
{
    public function create(CreateSellerDTOInterface $sellerDTO): Model;
}