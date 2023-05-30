<?php

declare(strict_types=1);

namespace App\Services\Auth;

interface AuthUserServiceInterface
{
    public function getToken(array $attributes, string $deviceName = 'auth_token'): string;

    public function revokeTokenByDeviceName(string $deviceName): void;

    public function revokeTokenById(int $id): void;

    public function revokeAllTokens(): void;
}