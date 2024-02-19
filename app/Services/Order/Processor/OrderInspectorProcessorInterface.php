<?php

declare(strict_types=1);

namespace App\Services\Order\Processor;

interface OrderInspectorProcessorInterface
{
    public function process(): int;
}