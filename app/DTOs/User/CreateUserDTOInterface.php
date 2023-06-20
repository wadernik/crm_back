<?php

declare(strict_types=1);

namespace App\DTOs\User;

use App\DTOs\Sub\EmailInterface;
use App\DTOs\Sub\ImmutablePhoneInterface;
use Illuminate\Contracts\Support\Arrayable;

interface CreateUserDTOInterface extends ImmutablePhoneInterface, EmailInterface, Arrayable
{
    // public function username(): string;

    public function password(): string;

    public function firstName(): string;

    public function lastName(): ?string;

    public function birthDate(): ?string;

    public function sex(): ?int;

    public function role(): int;
}