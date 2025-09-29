<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order\Product;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Dictionaries\OrderTitlesDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Dictionary\DictionaryTypeEnum;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class ListOrderProductController extends AbstractApiController
{
    public function __invoke(
        OrderTitlesDictionaryRequest $request,
        DictionaryRepositoryInterface $dictionaryRepository
    ): JsonResponse
    {
        $requestData = $request->validated();

        $requestData['filter']['type'] = DictionaryTypeEnum::PRODUCT_TITLE->value;
        $requestData['filter']['deleted_at'] = null;

        $sort = ['sort' => $requestData['sort'] ?? 'id', 'order' => $requestData['order'] ?? 'asc'];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $items = $dictionaryRepository->findAllBy(criteria: $requestData, sort: $sort, limit: $limit, offset: $offset);
        $total = $dictionaryRepository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }
}
