<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User\User;
use Illuminate\Support\Collection;

interface FindDevicesInterface
{
    public function devices(User $user, ?string $deviceName = null): Collection;
}