<?php

declare(strict_types=1);

namespace App\Services\Order\Report;

use App\DTOs\Report\AcceptedOrderDto;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

final class AcceptedOrdersReportExporter implements FromView, ShouldAutoSize
{
    /**
     * @param Collection<AcceptedOrderDto> $orders
     */
    public function __construct(
        private readonly Collection $orders,
        private readonly string $dateFrom,
        private readonly string $dateTo,
    ) {
    }

    public function view(): View
    {
        return view('reports.accepted_orders_report', [
            'orders' => $this->orders->toArray(),
            'dateFrom' => CarbonImmutable::parse($this->dateFrom)->format('d.m.Y'),
            'dateTo' => CarbonImmutable::parse($this->dateTo)->format('d.m.Y'),
        ]);
    }
}
