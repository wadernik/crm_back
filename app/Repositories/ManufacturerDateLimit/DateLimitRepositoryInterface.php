<?php

declare(strict_types=1);

namespace App\Repositories\ManufacturerDateLimit;

use App\Repositories\CountInterface;
use App\Repositories\FindAllByCriteriaInterface;
use App\Repositories\FindOneByIdInterface;

interface DateLimitRepositoryInterface extends FindAllByCriteriaInterface,
                                               FindOneByIdInterface,
                                               CountInterface,
                                               DateLimitLimitTypesInterface
{
}