<?php

declare(strict_types=1);

namespace App\Repositories\Notification;

use App\Models\User\User;

interface CountUnreadByUserInterface
{
    public function countUnreadByUser(User $user): int;
}