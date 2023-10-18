<?php

declare(strict_types=1);

namespace App\Repositories\Seller;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface SellerRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface, CountInterface
{
}