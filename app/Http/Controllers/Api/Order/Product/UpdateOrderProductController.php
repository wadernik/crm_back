<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Product;

use App\Attributes\Permission;
use App\DTOs\OrderProduct\UpdateProductDto;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\OrderProduct\CreateOrderProductRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\OrderProduct\OrderProductManager;
use App\Models\Dictionary\DictionaryTypeEnum;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('orders.edit')]
final class UpdateOrderProductController extends AbstractApiController
{
    public function __invoke(
        int $id,
        CreateOrderProductRequest $request,
        DictionaryRepositoryInterface $dictionaryRepository,
        OrderProductManager $manager,
    ): JsonResponse {
        $dto = new UpdateProductDto($request->validated());

        if (!$item = $dictionaryRepository->find($id, DictionaryTypeEnum::PRODUCT_TITLE->value)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $item = $manager->update($item, $dto);

        return ApiResponse::responseSuccess($item->toArray());
    }
}
