<?php

declare(strict_types=1);

namespace App\Services\Order\Enricher;

use App\DTOs\Order\Composite\OrderCompositeInterface;

interface OrderCompositeByCommentsEnricherInterface
{
    public function enrich(OrderCompositeInterface $composite): OrderCompositeInterface;

    /**
     * @param OrderCompositeInterface ...$composites
     *
     * @return array<OrderCompositeInterface>
     */
    public function enrichCollection(OrderCompositeInterface ...$composites): array;
}