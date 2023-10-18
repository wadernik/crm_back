<?php

declare(strict_types=1);

namespace App\Managers\User;

use App\DTOs\User\UpdateUserDTOInterface;
use App\Models\User\User;

interface UserUpdaterInterface
{
    public function update(User $user, UpdateUserDTOInterface $userDTO): User;
}