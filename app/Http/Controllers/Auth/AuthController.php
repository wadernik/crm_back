<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\BaseApiResponse;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use BaseApiResponse;

    public function register(CreateUserRequest $request, UserService $userService): JsonResponse
    {
        $validated = $request->validated();

        try {
            $userService->createUserAction($validated);
        } catch (\Exception $e) {
            // @TODO: do something about exception

            return $this->responseError(code: 500);
        }

        return $this->responseSuccess();
    }

    public function login(LoginRequest $request, UserService $userService): JsonResponse
    {
        $attributes = $request->validated();

        try {
            $token = $userService->getToken($attributes);

            if ($token === '') {
                return $this->responseError(code: 401);
            }

            return response()->json(data: [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            // @TODO: do something about exception

            Log::error($e->getMessage());

            return $this->responseError(code: 500);
        }
    }

    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return $this->responseSuccess(message: 'Current token was revoked');
    }
}
