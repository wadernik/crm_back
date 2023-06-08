<?php

declare(strict_types=1);


namespace App\Http\Controllers\Api\VK;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Managers\User\UserManagerInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RemoveVkTokenController extends AbstractApiController
{
    public function destroy(UserManagerInterface $manager): JsonResponse
    {
        if (!$this->isAllowed('vk.integration.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $manager->deleteToken(auth('sanctum')->id());

        return ApiResponse::responseSuccess();
    }
}