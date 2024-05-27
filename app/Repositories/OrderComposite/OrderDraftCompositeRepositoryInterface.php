<?php

declare(strict_types=1);

namespace App\Repositories\OrderComposite;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface OrderDraftCompositeRepositoryInterface extends FindAllByCriteriaInterface,
                                                         FindOneByIdInterface,
                                                         CountInterface
{
}