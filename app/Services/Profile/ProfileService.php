<?php

declare(strict_types=1);

namespace App\Services\Profile;

use App\Factory\Profile\ProfileFactoryInterface;
use App\Models\User\Sub\ProfileInterface;

final class ProfileService implements ProfileServiceInterface
{
    public function __construct(
        private readonly StyledUserAgentServiceInterface $userAgentService,
        private readonly ProfileFactoryInterface $factory,
    )
    {
    }

    public function profile(int $userId, ?string $userAgent = null): ?ProfileInterface
    {
        $device = $userAgent ? $this->userAgentService->agent($userAgent) : null;

        return $this->factory->new($userId, $device);
    }
}