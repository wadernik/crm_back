<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\Dictionaries\UserDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class UserDictionaryController
{
    public function __invoke(UserDictionaryRequest $request, UserRepositoryInterface $userRepository): JsonResponse
    {
        $attributes = [
            'id',
            'first_name',
            'last_name',
            'sex',
            'as_inspector',
        ];

        $requestData = $request->validated();

        $sort = [
            'sort' => $requestData['sort'] ?? 'id',
            'order' => $requestData['order'] ?? 'asc',
        ];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['page'] ?? null;

        $items = $userRepository->findAllBy(
            criteria: $requestData,
            attributes: $attributes,
            sort: $sort,
            limit: $limit,
            offset: $offset
        );
        $total = $userRepository->count($requestData);

        return ApiResponse::responseSuccess(data: $items->toArray(), total: $total);
    }
}