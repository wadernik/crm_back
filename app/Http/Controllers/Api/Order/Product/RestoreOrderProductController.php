<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Product;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Orders\OrderProductRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Order\Product\RestoreOrderProductByRequestServiceInterface;
use Illuminate\Http\JsonResponse;

#[Permission('orders.edit')]
final class RestoreOrderProductController extends AbstractApiController
{
    public function __invoke(
        OrderProductRequest $request,
        RestoreOrderProductByRequestServiceInterface $restoreProductService
    ): JsonResponse
    {
        $restoreProductService->restore($request);

        return ApiResponse::responseSuccess();
    }
}