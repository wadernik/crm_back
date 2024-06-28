<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Order;

use App\Http\Responses\ApiResponse;
use App\Models\Order\Contact\ContactTypeEnum;
use App\Models\Order\Item\DecorationTypeEnum;
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

    public function contactTypes(): JsonResponse
    {
        $contactTypes = ContactTypeEnum::asArray();

        return ApiResponse::responseSuccess(data: $contactTypes, total: count($contactTypes));
    }

    public function decorationTypes(): JsonResponse
    {
        $types = DecorationTypeEnum::asArray();

        return ApiResponse::responseSuccess(data: $types, total: count($types));
    }
}