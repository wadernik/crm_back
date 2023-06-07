<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\ListUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\User\UserManagerInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends AbstractApiController
{
    public function index(ListUserRequest $request, UserRepositoryInterface $userRepository): JsonResponse
    {
        if (!$this->isAllowed('users.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $attributes = ['id', 'first_name', 'last_name', 'role_id', 'last_seen', 'sex'];

        $requestData = $request->validated();

        $sort = ['sort' => $requestData['sort'] ?? null, 'order' => $requestData['order'] ?? null];
        $limit = $requestData['limit'] ?? null;
        $offset = $requestData['offset'] ?? null;

        $users = $userRepository->findAllBy($requestData, $attributes, $sort, $limit, $offset);
        $total = $userRepository->count($requestData);

        return ApiResponse::responseSuccess(data: $users->toArray(), total: $total);
    }

    public function show(int $id, UserRepositoryInterface $userRepository): JsonResponse
    {
        if (!$this->isAllowed('users.view')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$user = $userRepository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($user->toArray());
    }

    public function store(CreateUserRequest $request, UserManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $userDTO = new CreateUserDTO($request->validated());

        $user = $manager->create($userDTO);

        return ApiResponse::responseSuccess($user->toArray());
    }

    public function update(int $id, UpdateUserRequest $request, UserManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $userDTO = new UpdateUserDTO($request->validated());

        if (!$user = $manager->update($id, $userDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($user->toArray());
    }

    public function destroy(int $id, UserManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$user = $manager->delete($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($user->toArray());
    }
}