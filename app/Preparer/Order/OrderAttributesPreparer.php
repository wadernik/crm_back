<?php

declare(strict_types=1);

namespace App\Preparer\Order;

use function collect;

final class OrderAttributesPreparer implements OrderAttributesPreparerInterface
{
    public function prepare(array $attributes): array
    {
        $detailAttributes = [
            'name' => true,
            'amount' => true,
            'label' => 'true',
            'decoration' => true,
            'comment' => true
        ];

        return collect($attributes)
            ->partition(function (?string $value, string $key) use ($detailAttributes): bool {
                return !isset($detailAttributes[$key]);
            })
            ->toArray();
    }
}