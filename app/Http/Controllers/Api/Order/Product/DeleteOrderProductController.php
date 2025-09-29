<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Product;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Http\Responses\OrderProduct\OrderProductResponse;
use App\Managers\OrderProduct\OrderProductManager;
use App\Models\Dictionary\DictionaryTypeEnum;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('orders.edit')]
final class DeleteOrderProductController extends AbstractApiController
{
    public function __invoke(
        int $id,
        DictionaryRepositoryInterface $dictionaryRepository,
        OrderProductManager $manager,
    ): JsonResponse {
        if (!$item = $dictionaryRepository->find($id, DictionaryTypeEnum::PRODUCT_TITLE->value)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $manager->delete($item);

        return ApiResponse::responseSuccess((new OrderProductResponse($item))->toArray());
    }
}
