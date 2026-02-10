<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Report;

use App\Attributes\Permission;
use App\DTOs\Report\AcceptedOrdersRequestDto;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\AcceptedOrdersReportRequest;
use App\Services\Order\Report\AcceptedOrdersReporterService;
use App\Services\Order\Report\AcceptedOrdersReportExporter;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

#[Permission('orders.view')]
final class AcceptedOrdersReportController extends AbstractApiController
{
    public function __invoke(
        AcceptedOrdersReportRequest $request,
        AcceptedOrdersReporterService $reporter,
    ): BinaryFileResponse|JsonResponse {
        $requestDto = new AcceptedOrdersRequestDto($request->validated());

        $exporter = new AcceptedOrdersReportExporter(
            $reporter->get($requestDto),
            $requestDto->dateStart(),
            $requestDto->dateEnd(),
        );

        return Excel::download($exporter, "orders-{$requestDto->dateStart()}-{$requestDto->dateEnd()}.xlsx");
    }
}
