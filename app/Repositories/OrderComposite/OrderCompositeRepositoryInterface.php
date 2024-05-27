<?php

declare(strict_types=1);

namespace App\Repositories\OrderComposite;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface OrderCompositeRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface, CountInterface
{
}