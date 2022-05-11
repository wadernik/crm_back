<?php

namespace App\Services\Orders;

use Barryvdh\DomPDF\Facade\Pdf;

class OrderExportService
{
    /**
     * @param array $order
     * @param string $fileName
     * @return array [$filePath, $fileName]
     */
    public function exportPdf(array $order, string $fileName = ''): array
    {
        $fileName = $fileName === ''
            ? 'order_' . $order['order_date'] ?? ''
            : str_replace(['*', ':', '/', '\\', '?', '[', ']', '"', '«', '»', '#', ','], '', $fileName);

        $template = 'exports.orders.order_export_pdf';

        $pdfInstance = Pdf::loadView($template, $order);

        $tmpPDFPath = tempnam(sys_get_temp_dir(), $fileName);
        file_put_contents($tmpPDFPath, $pdfInstance->output());

        return [$tmpPDFPath, "$fileName.pdf"];
    }
}
