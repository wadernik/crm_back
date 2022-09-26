<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OAuth\Vk\VkCatchRedirectRequest;
use App\Services\External\Vk\VkService;
use App\Services\Users\UserInstanceService;
use Illuminate\Http\JsonResponse;

class VkController extends AbstractBaseApiController
{
    /**
     * @param VkService $vkService
     * @return JsonResponse
     */
    public function authorizeAppLink(VkService $vkService): JsonResponse
    {
        return $this->responseSuccess(['url' => $vkService->getUrlCode()]);
    }

    /**
     * @param VkCatchRedirectRequest $request
     * @param VkService $vkService
     * @param UserInstanceService $userInstanceService
     * @return JsonResponse
     */
    public function catchRedirect(
        VkCatchRedirectRequest $request,
        VkService $vkService,
        UserInstanceService $userInstanceService
    ): JsonResponse {
        $validated = $request->validated();

        if (isset($validated['code'])) {
            $token = $vkService->getAccessToken($validated['code']);
            $userInstanceService->setVkAccessToken(auth('sanctum')->id(), $token);
        }

        return $this->responseSuccess();
    }
}
