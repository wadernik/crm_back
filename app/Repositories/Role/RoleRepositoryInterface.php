<?php

declare(strict_types=1);

namespace App\Repositories\Role;

use App\Repositories\Sub\ApplyWithInterface;
use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface RoleRepositoryInterface extends FindAllByCriteriaInterface,
                                          FindOneByIdInterface,
                                          ApplyWithInterface,
                                          CountInterface
{
}