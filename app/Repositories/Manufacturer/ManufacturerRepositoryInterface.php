<?php

declare(strict_types=1);

namespace App\Repositories\Manufacturer;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;
use App\Repositories\Sub\FindOneByIdInterface;

interface ManufacturerRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface, CountInterface
{
}