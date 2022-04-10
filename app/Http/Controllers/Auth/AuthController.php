<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\BaseApiResponse;
use App\Services\AuthUsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use BaseApiResponse;

    public function __construct(
        private AuthUsersService $authUsersService
    ) {}

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
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
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authUsersService->revokeAllTokens();

            $request->session()->invalidate();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseSuccess(message: 'Current token was revoked');
    }

    /**
     * @return JsonResponse
     */
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
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
