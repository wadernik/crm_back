<?php

declare(strict_types=1);

namespace App\Repositories\Permission;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface PermissionRepositoryInterface extends FindAllByCriteriaInterface, CountInterface
{
}