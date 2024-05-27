<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderDraft\V2;

use App\Attributes\Permission;
use App\DTOs\Order\Composite\OrderCompositeInterface;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\ListOrderRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\OrderComposite\OrderDraftCompositeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use function array_map;

#[Permission('orders.view')]
final class ListOrderDraftController extends AbstractApiController
{
    public function __invoke(
        ListOrderRequest $request,
        OrderDraftCompositeRepositoryInterface $repository,
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
                $items->all()
            ),
            total: $total
        );
    }
}