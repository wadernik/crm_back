<?php

declare(strict_types=1);

namespace App\Managers\User;

use App\DTOs\User\CreateUserDTOInterface;
use App\Models\User\User;

interface UserCreatorInterface
{
    public function create(CreateUserDTOInterface $userDTO): User;
}