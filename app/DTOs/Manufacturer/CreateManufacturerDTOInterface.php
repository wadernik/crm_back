<?php

declare(strict_types=1);

namespace App\DTOs\Manufacturer;

use App\DTOs\Sub\EmailInterface;
use App\DTOs\Sub\ImmutableNameInterface;
use App\DTOs\Sub\PhoneInterface;

interface CreateManufacturerDTOInterface extends ImmutableNameInterface, PhoneInterface, EmailInterface
{
    public function address(): string;

    public function limit(): ?int;
}