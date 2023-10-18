<?php

declare(strict_types=1);

namespace App\Repositories\Seller;

use App\Models\Seller\Seller;

interface FindOneByIdInterface
{
    public function find(int $id): ?Seller;
}