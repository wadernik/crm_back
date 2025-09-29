<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Product;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\OrderProductRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Order\Product\DeleteOrderProductByRequestServiceInterface;
use Illuminate\Http\JsonResponse;

#[Permission('orders.edit')]
final class DeleteByRequestOrderProductController extends AbstractApiController
{
    public function __invoke(
        OrderProductRequest $request,
        DeleteOrderProductByRequestServiceInterface $restoreProductService
    ): JsonResponse
    {
        $restoreProductService->delete($request);

        return ApiResponse::responseSuccess();
    }
}
