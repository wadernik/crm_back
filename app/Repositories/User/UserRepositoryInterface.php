<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Repositories\CountInterface;
use App\Repositories\FindAllByCriteriaInterface;
use App\Repositories\FindAllByIdsInterface;
use App\Repositories\FindOneByIdInterface;

interface UserRepositoryInterface extends FindAllByCriteriaInterface,
                                          FindProfileWithRolesAndVKInterface,
                                          FindDevicesInterface,
                                          FindOneByIdInterface,
                                          FindAllByIdsInterface,
                                          CountInterface
{
}