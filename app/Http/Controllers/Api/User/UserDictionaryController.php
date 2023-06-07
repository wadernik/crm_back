<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\Dictionaries\UserDictionaryRequest;
use App\Http\Responses\ApiResponse;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class UserDictionaryController
{
    public function all(UserDictionaryRequest $request, UserRepositoryInterface $userRepository): JsonResponse
    {
        $attributes = ['id', 'first_name', 'last_name', 'sex'];

        $criteria = $request->validated();

        $users = $userRepository->findAllBy($criteria, $attributes);
        $total = $userRepository->count($criteria);

        return ApiResponse::responseSuccess(data: $users->toArray(), total: $total);
    }
}