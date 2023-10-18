<?php

declare(strict_types=1);

namespace App\Managers\Seller;

use App\Models\Seller\Seller;

interface SellerDeleterInterface
{
    public function delete(int $id): ?Seller;
}