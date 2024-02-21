<?php

declare(strict_types=1);

namespace App\DTOs\User;

use App\DTOs\Sub\EmailInterface;
use App\DTOs\Sub\PhoneInterface;
use Illuminate\Contracts\Support\Arrayable;

interface UpdateUserDTOInterface extends PhoneInterface, EmailInterface, Arrayable
{
    public function firstName(): ?string;

    public function lastName(): ?string;

    public function birthDate(): ?string;

    public function sex(): ?int;

    public function role(): ?int;

    public function asInspector(): bool;

    public function password(): ?string;
}