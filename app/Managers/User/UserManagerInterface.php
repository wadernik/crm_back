<?php

declare(strict_types=1);

namespace App\Managers\User;

interface UserManagerInterface extends UserCreatorInterface,
                                       UserUpdaterInterface,
                                       UserDeleterInterface,
                                       UserTokenCreatorInterface,
                                       UserTokenDeleterInterface
{
}