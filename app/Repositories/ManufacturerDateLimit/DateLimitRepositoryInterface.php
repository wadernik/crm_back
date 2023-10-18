<?php

declare(strict_types=1);

namespace App\Repositories\ManufacturerDateLimit;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface DateLimitRepositoryInterface extends FindAllByCriteriaInterface,
                                               FindOneByIdInterface,
                                               CountInterface,
                                               DateLimitLimitTypesInterface
{
}