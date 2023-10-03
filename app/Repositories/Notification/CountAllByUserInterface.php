<?php

declare(strict_types=1);

namespace App\Repositories\Notification;

use App\Models\User\User;

interface CountAllByUserInterface
{
    public function countByUser(User $user): int;
}