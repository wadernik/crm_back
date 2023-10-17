<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\DTOs\User\UserReportDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Reports\UserReportRequest;
use App\Http\Responses\ApiResponse;
use App\Services\User\ExportUserReportServiceInterface;
use App\Services\User\UserReportServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

final class ExportUserReportController extends AbstractApiController
{
    public function __invoke(
        UserReportRequest $request,
        UserReportServiceInterface $service,
        ExportUserReportServiceInterface $exportUserReportService
    ): BinaryFileResponse|JsonResponse
    {
        if (!$this->isAllowed('reports.view') || !$this->isAllowed('users_report.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $reportDTO = new UserReportDTO($request->validated());

        $report = $service->report($reportDTO);

        [$path, $filename] = $exportUserReportService->export($reportDTO, $report);

        return ApiResponse::responseBinary($path, $filename);
    }
}