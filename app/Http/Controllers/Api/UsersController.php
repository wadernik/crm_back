<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Users\ListUsersRequest;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends BaseApiController
{
    public function __construct(
        private UsersService $userService
    ) {}

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
            $records = $this->userService->getUsers($request->validated());

            return $this->responseSuccess(data: $records, headers: ['x-total-count' => count($records)]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param ListUsersRequest $request
     * @return JsonResponse
     */
    public function store(ListUsersRequest $request): JsonResponse
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

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        if (!$this->isAllowed('users.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        return $this->responseSuccess();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Jsonresponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$this->isAllowed('users.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        return $this->responseSuccess();
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

        return $this->responseSuccess();
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
}
