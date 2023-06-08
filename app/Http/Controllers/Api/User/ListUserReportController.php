<?php

declare(strict_types=1);


namespace App\Http\Controllers\Api\User;

use App\DTOs\User\UserReportDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Reports\UserReportRequest;
use App\Http\Responses\ApiResponse;
use App\Services\User\UserReportServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ListUserReportController extends AbstractApiController
{
    public function list(UserReportRequest $request, UserReportServiceInterface $service): JsonResponse
    {
        if (!$this->isAllowed('reports.view') || !$this->isAllowed('users_report.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $reportDTO = new UserReportDTO($request->validated());

        $report = $service->report($reportDTO);

        return ApiResponse::responseSuccess($report->toArray());
    }
}