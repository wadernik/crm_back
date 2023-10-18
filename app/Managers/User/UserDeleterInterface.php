<?php

declare(strict_types=1);

namespace App\Managers\User;

use App\Models\User\User;

interface UserDeleterInterface
{
    public function delete(User $user): User;
}