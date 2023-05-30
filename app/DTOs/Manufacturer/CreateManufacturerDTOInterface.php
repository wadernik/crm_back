<?php

declare(strict_types=1);

namespace App\DTOs\Manufacturer;

use App\DTOs\Sub\EmailInterface;
use App\DTOs\Sub\ImmutableNameInterface;
use App\DTOs\Sub\PhoneInterface;
use Illuminate\Contracts\Support\Arrayable;

interface CreateManufacturerDTOInterface extends ImmutableNameInterface, PhoneInterface, EmailInterface, Arrayable
{
    public function address(): string;

    public function limit(): ?int;
}