<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Report;

use App\Attributes\Permission;
use App\DTOs\Order\Composite\OrderCompositeInterface;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\OrderReportRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\OrderComposite\OrderCompositeRepositoryInterface;
use App\Services\Order\Enricher\OrderCompositeByCommentsEnricherInterface;
use Illuminate\Http\JsonResponse;

#[Permission('orders.view')]
final class OrderReportController extends AbstractApiController
{
    public function __invoke(
        OrderReportRequest $request,
        OrderCompositeRepositoryInterface $repository,
    ): JsonResponse {
        $requestData = $request->validated();

        $sort = [
            'sort' => $requestData['sort'] ?? null,
            'order' => $requestData['order'] ?? null,
        ];

        $limit = $requestData['limit'] ?? null;

        $offset = $requestData['page'] ?? null;

        $items = $repository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);

        $total = $repository->count($requestData);

        return ApiResponse::responseSuccess(
            data: array_map(
                static fn (OrderCompositeInterface $composite): array => $composite->toArray(),
                $items
            ),
            total: $total
        );
    }
}
