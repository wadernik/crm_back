<?php

declare(strict_types=1);

namespace App\Repositories\Seller;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;
use App\Repositories\Sub\FindOneByIdInterface;

interface SellerRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface, CountInterface
{
}