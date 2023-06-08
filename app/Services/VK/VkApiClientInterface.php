<?php

declare(strict_types=1);


namespace App\Services\VK;

interface VkApiClientInterface
{
    public function codeUrl(): string;

    public function accessToken(string $code): array;
}