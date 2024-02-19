<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Repositories\Sub\CountInterface;
use App\Repositories\Sub\FindAllByCriteriaInterface;
use App\Repositories\Sub\FindAllByIdsInterface;

interface UserRepositoryInterface extends FindAllByCriteriaInterface,
                                          FindAllInterface,
                                          FindProfileWithRolesAndVKInterface,
                                          FindDevicesInterface,
                                          FindOneByIdInterface,
                                          FindAllByIdsInterface,
                                          CountInterface,
                                          UserStatusesInterface,
                                          FindAllByInspectorInterface
{
}