<?php

declare(strict_types=1);

namespace App\Services\Profile;

interface StyledUserAgentServiceInterface
{
    public function agent(string $userAgent): string;
}