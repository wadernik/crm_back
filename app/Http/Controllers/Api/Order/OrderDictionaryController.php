<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Http\Requests\Dictionaries\OrderTitlesDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Dictionary\DictionaryTypeEnum;
use App\Models\Order\Item\DecorationTypeEnum;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use App\Services\Order\Contact\OrderContactTypeRetrieverInterface;
use App\Services\Order\Status\OrderStatusesRetrieverInterface;
use Illuminate\Http\JsonResponse;
use function count;

final class OrderDictionaryController
{
    public function statuses(OrderStatusesRetrieverInterface $statusesRetriever): JsonResponse
    {
        $statuses = $statusesRetriever->get();
        return ApiResponse::responseSuccess(data: $statuses, total: count($statuses));
    }

    public function titles(
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

    public function contactTypes(OrderContactTypeRetrieverInterface $retriever): JsonResponse
    {
        $contactTypes = $retriever->get();

        return ApiResponse::responseSuccess(data: $contactTypes, total: count($contactTypes));
    }

    public function decorationTypes(): JsonResponse
    {
        $types = DecorationTypeEnum::asArray();

        return ApiResponse::responseSuccess(data: $types, total: count($types));
    }
}