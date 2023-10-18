<?php

declare(strict_types=1);

namespace App\Repositories\Order\Item;

use App\Repositories\Sub\FindAllByCriteriaInterface;

interface OrderItemRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface
{
}