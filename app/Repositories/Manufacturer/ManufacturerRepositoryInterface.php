<?php

declare(strict_types=1);

namespace App\Repositories\Manufacturer;

use App\Repositories\CountInterface;
use App\Repositories\FindAllByCriteriaInterface;
use App\Repositories\FindOneByIdInterface;

interface ManufacturerRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface, CountInterface
{
}