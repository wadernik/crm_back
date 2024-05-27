<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\OrderDraft\V2;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Repositories\OrderComposite\OrderDraftCompositeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('orders.view')]
final class GetOrderDraftController extends AbstractApiController
{
    public function __invoke(int $id, OrderDraftCompositeRepositoryInterface $repository): JsonResponse
    {
        if (!$order = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($order->toArray());
    }
}