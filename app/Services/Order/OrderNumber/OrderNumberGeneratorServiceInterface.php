<?php

declare(strict_types=1);

namespace App\Services\Order\OrderNumber;

interface OrderNumberGeneratorServiceInterface
{
    public function generate(string $orderDate): string;
}