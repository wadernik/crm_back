<?php

declare(strict_types=1);

namespace App\Models\Order\Item;

/**
 * @property int id
 * @property int order_id
 */
interface OrderItemInterface
{
    /**
     * @return array<int>
     */
    public function getFilesCollection(): array;

    /**
     * @var array<int> $files
     */
    public function setFilesCollection(array $files): void;
}