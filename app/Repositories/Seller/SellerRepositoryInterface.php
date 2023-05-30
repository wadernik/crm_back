<?php

declare(strict_types=1);

namespace App\Repositories\Seller;

use App\Repositories\CountInterface;
use App\Repositories\FindAllByCriteriaInterface;
use App\Repositories\FindOneByIdInterface;

interface SellerRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface, CountInterface
{
}