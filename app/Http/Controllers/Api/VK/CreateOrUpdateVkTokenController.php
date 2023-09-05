<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\VK;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\OAuth\Vk\VkCatchRedirectRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\User\UserManagerInterface;
use App\Services\VK\VkServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateOrUpdateVkTokenController extends AbstractApiController
{
    public function createOrUpdate(
        VkCatchRedirectRequest $request,
        VkServiceInterface $service,
        UserManagerInterface $manager
    ): JsonResponse
    {
        if (!$this->isAllowed('vk.integration.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();

        $token = '';

        if (isset($validated['code'])) {
            $token = $service->accessToken($validated['code']);
        } elseif (isset($validated['access_token'])) {
            $token = $validated['access_token'];
        }

        $manager->createToken($this->userId(), $token);

        return ApiResponse::responseSuccess();
    }
}