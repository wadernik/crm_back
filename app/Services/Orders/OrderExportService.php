<?php

namespace App\Services\Orders;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class OrderExportService
{
    /**
     * @param array $orders
     * @param string $fileName
     * @return array [$filePath, $fileName]
     */
    public function exportPdf(array $orders, string $fileName = ''): array
    {
        $dateFormatted = Carbon::now()->format('Y-m-d');
        if (count($orders) === 1) {
            $order = array_shift($orders);
            $dateFormatted = Carbon::parse($order['order_date'])->format('Y-m-d');
        }

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
