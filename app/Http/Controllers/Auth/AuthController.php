<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\BaseApiResponse;
use App\Services\AuthUsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use BaseApiResponse;

    public function __construct(
        private AuthUsersService $authUsersService
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        try {
            $token = $this->authUsersService->getToken($attributes);

            if ($token === '') {
                return $this->responseError(code: Response::HTTP_UNAUTHORIZED);
            }

            return response()->json(data: [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authUsersService->revokeAllTokens();
        } catch (\Exception $e) {
            Log::info($e->getMessage());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseSuccess(message: 'Current token was revoked');
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = $this->authUsersService->refreshToken();

            if ($token === '') {
                return $this->responseError(code: Response::HTTP_UNAUTHORIZED);
            }

            return response()->json(data: [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
