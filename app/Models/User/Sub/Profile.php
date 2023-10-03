<?php

declare(strict_types=1);

namespace App\Models\User\Sub;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use function array_merge;

final class Profile implements ProfileInterface
{
    /**
     * @param Model $user
     * @param Collection<DeviceInterface> $devices
     */
    public function __construct(private readonly Model $user, private readonly Collection $devices)
    {
    }

    public function user(): Model
    {
        return $this->user;
    }

    public function devices(): Collection
    {
        return $this->devices;
    }

    public function toArray(): array
    {
        $devicesNormalized = [];

        foreach ($this->devices as $device) {
            $devicesNormalized[] = [
                'id' => $device->id(),
                'name' => $device->name(),
                'last_used_at' => $device->lastUsedAt(),
                'current_device' => $device->current(),
            ];
        }

        return array_merge($this->user->toArray(), ['devices' => $devicesNormalized]);
    }
}