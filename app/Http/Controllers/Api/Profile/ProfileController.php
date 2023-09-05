<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\DTOs\User\UpdateUserDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\User\UserManagerInterface;
use App\Services\Auth\AuthUserServiceInterface;
use App\Services\Profile\ProfileServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProfileController extends AbstractApiController
{
    public function profile(Request $request, ProfileServiceInterface $profileService): JsonResponse
    {
        if (!$userId = $this->userId()) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if (!$profile = $profileService->profile($userId, $request->userAgent())) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($profile->toArray());
    }

    public function update(int $id, UpdateUserRequest $request, UserManagerInterface $manager): JsonResponse
    {
        if (!$userId = $this->userId()) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        if ($userId !== $id) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $userDTO = new UpdateUserDTO($request->validated());

        if (!$user = $manager->update($id, $userDTO)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($user->toArray());
    }

    public function revokeDevice(int $tokenId, AuthUserServiceInterface $service): JsonResponse
    {
        if (!$this->userId()) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $service->revokeTokenById($tokenId);

        return ApiResponse::responseSuccess();
    }
}