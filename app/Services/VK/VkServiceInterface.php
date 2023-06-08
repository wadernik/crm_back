<?php

declare(strict_types=1);

namespace App\Services\VK;

interface VkServiceInterface
{
    public function urlCode(): string;

    public function accessToken(string $code): ?string;
}