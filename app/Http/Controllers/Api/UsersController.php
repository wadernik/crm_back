<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Requests\Users\ListUsersRequest;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends BaseApiController
{
    public function __construct(
        private UsersService $userService
    ) {}

    public function all(): JsonResponse
    {
        try {
            $attributes = ['id', 'first_name', 'last_name'];
            $records = $this->userService->getUsers($attributes);

            return $this->responseSuccess(data: $records, headers: ['x-total-count' => count($records)]);
        } Catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param ListUsersRequest $request
     * @return JsonResponse
     */
    public function index(ListUsersRequest $request): JsonResponse
    {
        if (!$this->isAllowed('users.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $attributes = ['id', 'first_name', 'last_name', 'email', 'role_id', 'last_seen'];
            $records = $this->userService->getUsers($attributes, $request->validated());

            return $this->responseSuccess(data: $records, headers: ['x-total-count' => count($records)]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();

        try {
            $userId = $this->userService->createUser($validated);

            return $this->responseSuccess(data: ['id' => $userId], code: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $attributes = ['id', 'first_name', 'last_name', 'email', 'role_id'];
            $user = $this->userService->getUser($id, $attributes);

            if (!$user) {
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
     * @param UpdateUserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $user = $this->userService->getUser($id);

            if (!$user) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            if (!$this->userService->editUser($id, $request->validated())) {
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
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        try {
            $user = $this->userService->getUser($id);

            if (!$user) {
                return $this->responseError(code: Response::HTTP_NOT_FOUND);
            }

            $this->userService->deleteUser($id);

            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * This method is unused => not allowed
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        return $this->responseError(
            code: Response::HTTP_METHOD_NOT_ALLOWED,
            message: Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED]
        );
    }

    /**
     * This method is unused => not allowed
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        return $this->responseError(
            code: Response::HTTP_METHOD_NOT_ALLOWED,
            message: Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED]
        );
    }
}
