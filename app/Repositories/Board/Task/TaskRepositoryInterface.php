<?php

declare(strict_types=1);

namespace App\Repositories\Board\Task;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface TaskRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface, CountInterface
{
}