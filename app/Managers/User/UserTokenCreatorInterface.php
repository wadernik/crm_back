<?php

declare(strict_types=1);


namespace App\Managers\User;

interface UserTokenCreatorInterface
{
    public function createToken(int $id, string $accessToken): void;
}