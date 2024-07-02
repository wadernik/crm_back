<?php

declare(strict_types=1);

namespace App\Repositories\Setting;

use App\Repositories\Sub\FindAllByCriteriaInterface;

interface SettingRepositoryInterface extends FindAllByCriteriaInterface,
                                             FindOneByIdInterface,
                                             FindOneByTypeIdInterface
{
}