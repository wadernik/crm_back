<?php

declare(strict_types=1);

namespace App\Repositories\OrderSetting;

use App\Repositories\Sub\FindAllByCriteriaInterface;

interface OrderSettingRepositoryInterface extends FindAllByCriteriaInterface,
                                                  FindOneByIdInterface,
                                                  FindOneByTypeIdInterface
{
}