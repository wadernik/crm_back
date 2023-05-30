<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\AuthUserServiceInterface;
use App\Services\Profile\StyledUserAgentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractApiController
{
    public function __construct(private AuthUserServiceInterface $authUsersService)
    {
    }

    public function login(LoginRequest $request, StyledUserAgentServiceInterface $userAgent): JsonResponse
    {
        $attributes = $request->validated();
        $deviceName = $userAgent->agent($request->header('user-agent'));

        $token = $this->authUsersService->getToken($attributes, $deviceName);

        if ($token === '') {
            return ApiResponse::responseError(REsponse::HTTP_UNAUTHORIZED);
        }

        return response()->json(data: [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authUsersService->revokeAllTokens();

        $request->session()->invalidate();

        return ApiResponse::responseSuccess();
    }
}