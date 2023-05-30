<?php

declare(strict_types=1);

namespace App\Services\Profile;

use App\Models\User\Sub\ProfileInterface;

interface ProfileServiceInterface
{
    public function profile(int $userId, ?string $userAgent = null): ProfileInterface;
}