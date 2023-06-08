<?php

declare(strict_types=1);


namespace App\Managers\User;

interface UserTokenDeleterInterface
{
    public function deleteToken(int $id): void;
}