<?php

declare(strict_types=1);

namespace App\Models\User\Sub;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ProfileInterface extends Arrayable
{
    public function user(): Model;

    /**
     * @return Collection<DeviceInterface>
     */
    public function devices(): Collection;
}