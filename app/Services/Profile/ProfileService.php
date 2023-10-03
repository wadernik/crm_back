<?php

declare(strict_types=1);

namespace App\Services\Profile;

use App\Factory\Profile\ProfileFactoryInterface;
use App\Models\User\Sub\ProfileInterface;
use function app;

final class ProfileService implements ProfileServiceInterface
{
    private StyledUserAgentServiceInterface $userAgentService;
    private ProfileFactoryInterface $factory;

    public function __construct()
    {
        $this->userAgentService = app(StyledUserAgentServiceInterface::class);
        $this->factory = app(ProfileFactoryInterface::class);
    }

    public function profile(int $userId, ?string $userAgent = null): ?ProfileInterface
    {
        $device = $userAgent ? $this->userAgentService->agent($userAgent) : null;

        return $this->factory->new($userId, $device);
    }
}