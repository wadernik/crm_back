<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\AuthUsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractApiController
{
    public function __construct(private AuthUsersService $authUsersService)
    {
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        $deviceName = $this->getStyledUserAgent($request->header('user-agent'));

        $token = $this->authUsersService->getToken($attributes, $deviceName);

        if ($token === '') {
            return ApiResponse::responseError(REsponse::HTTP_UNAUTHORIZED);
        }

        return response()->json(data: [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authUsersService->revokeAllTokens();

        $request->session()->invalidate();

        return ApiResponse::responseSuccess();
    }
}