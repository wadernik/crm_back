<?php

declare(strict_types=1);

namespace App\Repositories\User;

use Illuminate\Database\Eloquent\Model;

interface FindProfileWithRolesAndVKInterface
{
    public function profile(int $userId): ?Model;
}