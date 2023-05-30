<?php

declare(strict_types=1);

namespace App\Managers\Seller;

use App\DTOs\Seller\UpdateSellerDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface SellerUpdaterInterface
{
    public function update(int $id, UpdateSellerDTOInterface $sellerDTO): ?Model;
}