<?php

declare(strict_types=1);

namespace App\Factory\Profile;

use App\Models\User\Sub\Profile;
use App\Models\User\Sub\ProfileInterface;
use App\Repositories\User\UserRepositoryInterface;

final class ProfileFactory implements ProfileFactoryInterface
{
    private UserRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = app(UserRepositoryInterface::class);
    }

    public function new(int $userId, ?string $device = null): ?ProfileInterface
    {
        if (!$user = $this->repository->profile($userId)) {
            return null;
        }

        $devices = $this->repository->devices($user, $device);

        return new Profile($user, $devices);
    }
}