<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\V2;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Repositories\OrderComposite\OrderCompositeRepositoryInterface;
use App\Services\Order\Enricher\OrderCompositeByCommentsEnricherInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('orders.view')]
final class GetOrderController extends AbstractApiController
{
    public function __invoke(
        int $id,
        OrderCompositeRepositoryInterface $repository,
        OrderCompositeByCommentsEnricherInterface $enricher
    ): JsonResponse {
        if (!$order = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $order = $enricher->enrich($order);

        return ApiResponse::responseSuccess($order->toArray());
    }
}
