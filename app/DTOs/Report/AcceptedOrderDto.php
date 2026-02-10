<?php

declare(strict_types=1);

namespace App\DTOs\Report;

use Illuminate\Support\Collection;

final class AcceptedOrderDto
{
    /**
     * @param Collection<AcceptedOrderItemDto> $items
     */
    public function __construct(
        public readonly int $id,
        public readonly string $number,
        public readonly string $status,
        public readonly string $source,
        public readonly string $buyer,
        public readonly string $acceptedDate,
        public readonly string $orderDate,
        public readonly string $orderTime,
        public readonly string $price,
        public readonly Collection $items,
        public readonly ?string $seller = null,
        public readonly ?string $numberExternal = null,
        public readonly ?string $inspector = null,
        public readonly ?string $phone = null,
    ) {
    }
}
