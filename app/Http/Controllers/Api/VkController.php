<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OAuth\Vk\VkCatchRedirectRequest;
use App\Services\External\Vk\VkService;
use App\Services\Users\UserInstanceService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VkController extends AbstractBaseApiController
{
    /**
     * @param VkService $vkService
     * @return JsonResponse
     */
    public function authorizeAppLink(VkService $vkService): JsonResponse
    {
        if (!$this->isAllowed('vk.integration.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

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
        if (!$this->isAllowed('vk.integration.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();

        $token = '';

        if (isset($validated['code'])) {
            $token = $vkService->getAccessToken($validated['code']);
        } elseif (isset($validated['access_token'])) {
            $token = $validated['access_token'];
        }

        $userInstanceService->setVkAccessToken(auth('sanctum')->id(), $token);

        return $this->responseSuccess();
    }

    public function removeToken(UserInstanceService $userInstanceService): JsonResponse
    {
        if (!$this->isAllowed('vk.integration.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $userInstanceService->removeAccessToken(auth('sanctum')->id());

        return $this->responseSuccess();
    }
}