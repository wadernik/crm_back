<?php

declare(strict_types=1);

namespace App\Repositories\Role;

use App\Repositories\CountInterface;
use App\Repositories\FindAllByCriteriaInterface;
use App\Repositories\FindOneByIdInterface;

interface RoleRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface, CountInterface
{
}