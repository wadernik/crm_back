<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Product;

use App\Attributes\Permission;
use App\DTOs\OrderProduct\CreateProductDto;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\OrderProduct\CreateOrderProductRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\OrderProduct\OrderProductManager;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('orders.edit')]
final class CreateOrderProductController extends AbstractApiController
{
    public function __invoke(
        CreateOrderProductRequest $request,
        DictionaryRepositoryInterface $dictionaryRepository,
        OrderProductManager $manager,
    ): JsonResponse {
        $dto = new CreateProductDto($request->validated());

        if (!$item = $manager->create($dto)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($item->toArray());
    }
}
