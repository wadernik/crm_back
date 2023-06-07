<?php

declare(strict_types=1);

namespace App\Services\Order\Export;

interface OrderExportServiceInterface
{
    /**
     * @return array<string, string>
     */
    public function export(array $orders, string $fileName = ''): array;
}