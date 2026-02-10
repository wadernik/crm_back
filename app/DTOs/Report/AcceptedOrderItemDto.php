<?php

declare(strict_types=1);

namespace App\DTOs\Report;

final class AcceptedOrderItemDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $unit,
        public readonly string $amount,
    ) {
    }
}
