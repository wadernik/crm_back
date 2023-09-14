<?php

declare(strict_types=1);

namespace App\Repositories\Dictionary;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;

interface DictionaryRepositoryInterface extends FindAllByCriteriaInterface, CountInterface
{
}