<?php

declare(strict_types=1);

namespace App\Preparer\Order;

interface OrderAttributesPreparerInterface
{
    /**
     * @param array $attributes
     *
     * @return array<array, array>
     */
    public function prepare(array $attributes): array;
}