<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\PrintOrderRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\Order\Export\OrderExportServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

final class ExportOrderController extends AbstractApiController
{
    public function export(
        PrintOrderRequest $request,
        OrderRepositoryInterface $repository,
        OrderExportServiceInterface $exportService
    ): BinaryFileResponse|JsonResponse
    {
        if (!$this->isAllowed('orders.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $criteria = $request->validated();

        $repository->applyWith(['files:id,filename', 'manufacturer', 'seller', 'source']);

        $orders = $repository->findAllBy(['filter' => $criteria]);

        [$path, $fileName] = $exportService->export($orders->toArray());

        return ApiResponse::responseBinary($path, $fileName);
    }
}