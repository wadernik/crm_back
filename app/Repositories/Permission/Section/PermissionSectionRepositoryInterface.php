<?php

declare(strict_types=1);

namespace App\Repositories\Permission\Section;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface PermissionSectionRepositoryInterface extends FindAllByCriteriaInterface, CountInterface
{
}