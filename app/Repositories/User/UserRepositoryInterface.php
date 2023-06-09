<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;
use App\Repositories\Sub\FindAllByIdsInterface;
use App\Repositories\Sub\FindOneByIdInterface;

interface UserRepositoryInterface extends FindAllByCriteriaInterface,
                                          FindProfileWithRolesAndVKInterface,
                                          FindDevicesInterface,
                                          FindOneByIdInterface,
                                          FindAllByIdsInterface,
                                          CountInterface,
                                          UserStatusesInterface
{
}