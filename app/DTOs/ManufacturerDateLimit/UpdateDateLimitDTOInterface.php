<?php

declare(strict_types=1);

namespace App\DTOs\ManufacturerDateLimit;

use Illuminate\Contracts\Support\Arrayable;

interface UpdateDateLimitDTOInterface extends Arrayable
{
    public function manufacturerId(): ?int;

    public function date(): ?string;

    public function limitType(): ?int;
}