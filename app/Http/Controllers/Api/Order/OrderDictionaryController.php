<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Http\Responses\ApiResponse;
use App\Models\Dictionary\DictionaryTypeEnum;
use App\Repositories\Dictionary\DictionaryRepositoryInterface;
use App\Services\Order\Status\OrderStatusesRetrieverInterface;
use Illuminate\Http\JsonResponse;

final class OrderDictionaryController
{
    public function statuses(OrderStatusesRetrieverInterface $statusesRetriever): JsonResponse
    {
        return ApiResponse::responseSuccess($statusesRetriever->get());
    }

    public function titles(DictionaryRepositoryInterface $dictionaryRepository): JsonResponse
    {
        return ApiResponse::responseSuccess($dictionaryRepository->findAllBy([
            'type' => DictionaryTypeEnum::PRODUCT_TITLE->value,
            'deleted_at' => null,
        ])->toArray());
    }
}