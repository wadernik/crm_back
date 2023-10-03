<?php

declare(strict_types=1);


namespace App\Services\User;

use App\DTOs\User\UserReportDTOInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use function file_put_contents;
use function sys_get_temp_dir;
use function tempnam;

final class ExportUserReportService implements ExportUserReportServiceInterface
{
    public function export(UserReportDTOInterface $reportDTO, Collection $report): array
    {
        $dateStartFormatted = Carbon::parse($reportDTO->dateStart())->format('d.m.Y');
        $dateEndFormatted = $reportDTO->dateEnd()
            ? Carbon::parse($reportDTO->dateEnd())->format('d.m.Y')
            : '';

        $fileName = 'Отчет по продажам сотрудников '
            . $dateStartFormatted . ($dateEndFormatted !== '' ? " $dateEndFormatted" : '') . '.pdf';

        $template = 'reports.users_report_view';

        $templateData = [
            'dateStart' => $dateStartFormatted,
            'dateEnd' => $dateEndFormatted !== '' ? $dateEndFormatted : null,
            'total' => $report->toArray()
        ];

        $pdfInstance = Pdf::loadView($template, $templateData);

        $tmpPDFPath = tempnam(sys_get_temp_dir(), $fileName);
        file_put_contents($tmpPDFPath, $pdfInstance->output());

        return [$tmpPDFPath, $fileName];
    }
}