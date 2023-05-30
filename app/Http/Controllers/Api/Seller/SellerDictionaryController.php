<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Dictionaries\SellerDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\Seller\SellerRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class SellerDictionaryController extends AbstractApiController
{
    public function all(SellerDictionaryRequest $request, SellerRepositoryInterface $repository): JsonResponse
    {
        $criteria = $request->validated();

        $items = $repository->findAllBy($criteria);
        $total = $repository->count($criteria);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }
}