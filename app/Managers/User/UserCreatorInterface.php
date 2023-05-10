<?php

declare(strict_types=1);

namespace App\Managers\User;

use App\DTOs\User\CreateUserDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface UserCreatorInterface
{
    public function create(CreateUserDTOInterface $userDTO): Model;
}