<?php

declare(strict_types=1);

namespace App\Repositories\ManufacturerDateLimit;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;
use App\Repositories\Sub\FindOneByIdInterface;

interface DateLimitRepositoryInterface extends FindAllByCriteriaInterface,
                                               FindOneByIdInterface,
                                               CountInterface,
                                               DateLimitLimitTypesInterface
{
}