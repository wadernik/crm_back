<?php

declare(strict_types=1);

namespace App\Repositories\Permission;

use App\Repositories\CountInterface;
use App\Repositories\FindAllByCriteriaInterface;

interface PermissionRepositoryInterface extends FindAllByCriteriaInterface, CountInterface
{
}