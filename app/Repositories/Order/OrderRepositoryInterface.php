<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface OrderRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface, CountInterface
{
}