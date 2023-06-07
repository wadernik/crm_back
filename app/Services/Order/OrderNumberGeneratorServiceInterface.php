<?php

declare(strict_types=1);

namespace App\Services\Order;

interface OrderNumberGeneratorServiceInterface
{
    public function generate(): string;
}