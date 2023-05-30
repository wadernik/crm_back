<?php

declare(strict_types=1);

namespace App\DTOs\ManufacturerDateLimit;

use Illuminate\Contracts\Support\Arrayable;

interface CreateDateLimitDTOInterface extends Arrayable
{
    public function manufacturerId(): int;

    public function date(): ?string;

    public function dates(): ?array;

    public function limitType(): int;
}