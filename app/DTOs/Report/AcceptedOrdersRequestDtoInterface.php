<?php

declare(strict_types=1);

namespace App\DTOs\Report;

use Illuminate\Contracts\Support\Arrayable;

interface AcceptedOrdersRequestDtoInterface extends Arrayable
{
    public function dateStart(): string;

    public function dateEnd(): ?string;
}
