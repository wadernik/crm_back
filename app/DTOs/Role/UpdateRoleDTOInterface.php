<?php

declare(strict_types=1);

namespace App\DTOs\Role;

use App\DTOs\Sub\LabelInterface;
use App\DTOs\Sub\NameInterface;
use Illuminate\Contracts\Support\Arrayable;

interface UpdateRoleDTOInterface extends NameInterface, LabelInterface, Arrayable
{
    public function permissions(): array;
}