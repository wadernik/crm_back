<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User\User;

interface FindProfileWithRolesAndVKInterface
{
    public function profile(int $userId): ?User;
}