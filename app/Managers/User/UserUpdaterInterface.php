<?php

declare(strict_types=1);

namespace App\Managers\User;

use App\DTOs\User\UpdateUserDTOInterface;
use Illuminate\Database\Eloquent\Model;

interface UserUpdaterInterface
{
    public function update(int $id, UpdateUserDTOInterface $userDTO): ?Model;
}