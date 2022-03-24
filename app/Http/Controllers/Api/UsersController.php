<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateUserRequest;
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
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if (!$this->isAllowed('users.view')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
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
            $userId = $this->userService->createUserAction($validated);

            return $this->responseSuccess(data: ['id' => $userId]);
        } catch (\Exception $e) {
            // @TODO: do something about exception
            Log::error("UsersController|STORE: " . $e->getMessage());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
