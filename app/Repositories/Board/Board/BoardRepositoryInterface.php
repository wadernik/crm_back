<?php

declare(strict_types=1);

namespace App\Repositories\Board\Board;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface BoardRepositoryInterface extends FindAllByCriteriaInterface, FindOneByIdInterface, CountInterface
{
}