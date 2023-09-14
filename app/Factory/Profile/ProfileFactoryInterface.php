<?php

declare(strict_types=1);

namespace App\Factory\Profile;

use App\Models\User\Sub\ProfileInterface;

interface ProfileFactoryInterface
{
    public function new(int $userId, ?string $device = null): ?ProfileInterface;
}