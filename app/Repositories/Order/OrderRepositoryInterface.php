<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use App\Repositories\Sub\ApplyJoinInterface;
use App\Repositories\Sub\ApplyWithInterface;
use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;
use App\Repositories\Sub\FindOneByIdInterface;

interface OrderRepositoryInterface extends FindAllByCriteriaInterface,
                                           FindOneByIdInterface,
                                           ApplyJoinInterface,
                                           ApplyWithInterface,
                                           OrderStatusInterface,
                                           CountInterface
{
}