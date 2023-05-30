<?php

declare(strict_types=1);

namespace App\Repositories\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface FindDevicesInterface
{
    public function devices(Model $user, ?string $deviceName = null): Collection;
}