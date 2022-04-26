<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Dictionaries\UsersDictionaryRequest;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Requests\Users\ListUsersRequest;
use App\Services\Users\UserInstanceService;
use App\Services\Users\UsersCollectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends BaseApiController
{
    /**
     * Dictionary
     * @param UsersDictionaryRequest $request
     * @param UsersCollectionService $usersCollectionService
     * @return JsonResponse
     */
    public function all(UsersDictionaryRequest $request, UsersCollectionService $usersCollectionService): JsonResponse
    {
        try {
            $attributes = ['id', 'first_name', 'last_name'];
            $records = $usersCollectionService->getInstances(
                attributes: $attributes,
                requestParams: $request->validated()
            );

            return $this->responseSuccess(data: $records, headers: ['x-total-count' => count($records)]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param ListUsersRequest $request
     * @param UsersCollectionService $usersCollectionService
     * @return JsonResponse
     */
    public function index(ListUsersRequest $request, UsersCollectionService $usersCollectionService): JsonResponse
    {
        if (!$this->isAllowed('users.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $attributes = ['id', 'first_name', 'last_name', 'email', 'role_id', 'last_seen'];
            $records = $usersCollectionService->getInstances($attributes, $request->validated());

            return $this->responseSuccess(data: $records, headers: ['x-total-count' => count($records)]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param CreateUserRequest $request
     * @param UserInstanceService $userInstanceService
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request, UserInstanceService $userInstanceService): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $user = $userInstanceService->createInstance($request->validated());

            return $this->responseSuccess(data: ['id' => $user['id']], code: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param UserInstanceService $userInstanceService
     * @return JsonResponse
     */
    public function show(int $id, UserInstanceService $userInstanceService): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $attributes = ['id', 'first_name', 'last_name', 'email', 'role_id', 'last_seen'];
            if (!$user = $userInstanceService->getInstance($id, $attributes)) {

                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess(data: $user);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param UpdateUserRequest $request
     * @param UserInstanceService $userInstanceService
     * @return JsonResponse
     */
    public function update(int $id, UpdateUserRequest $request, UserInstanceService $userInstanceService): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$userInstanceService->editInstance($id, $request->validated())) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @param UserInstanceService $userInstanceService
     * @return JsonResponse
     */
    public function destroy(int $id, UserInstanceService $userInstanceService): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            if (!$userInstanceService->deleteInstance($id)) {
                return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
