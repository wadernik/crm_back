<?php

declare(strict_types=1);

namespace App\DTOs\Role;

use App\DTOs\Sub\ImmutableNameInterface;
use App\DTOs\Sub\LabelInterface;
use Illuminate\Contracts\Support\Arrayable;

interface CreateRoleDTOInterface extends ImmutableNameInterface, LabelInterface, Arrayable
{
    public function permissions(): array;
}