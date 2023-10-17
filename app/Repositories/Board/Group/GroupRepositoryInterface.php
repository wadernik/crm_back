<?php

declare(strict_types=1);

namespace App\Repositories\Board\Group;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface GroupRepositoryInterface extends FindAllByCriteriaInterface,
                                           FindOneByIdInterface,
                                           CountInterface
{
}