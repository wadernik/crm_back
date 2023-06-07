<?php

declare(strict_types=1);

namespace App\Services\Order\Export;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

final class OrderExportService implements OrderExportServiceInterface
{
    public function export(array $orders, string $fileName = ''): array
    {
        $dateFormatted = Carbon::now()->timestamp;

        $fileName = $fileName === ''
            ? "orders_$dateFormatted" ?? ''
            : str_replace(['*', ':', '/', '\\', '?', '[', ']', '"', '«', '»', '#', ','], '', $fileName);

        $template = 'exports.orders.order_export_pdf';

        $pdfInstance = Pdf::loadView($template, ['orders' => $orders]);

        $tmpPDFPath = tempnam(sys_get_temp_dir(), $fileName);
        file_put_contents($tmpPDFPath, $pdfInstance->output());

        return [$tmpPDFPath, "$fileName.pdf"];
    }
}