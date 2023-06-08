<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\VK;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Services\VK\VkServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AuthVkAppController extends AbstractApiController
{
    public function auth(VkServiceInterface $service): JsonResponse
    {
        if (!$this->isAllowed('vk.integration.edit')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        return ApiResponse::responseSuccess(['url' => $service->urlCode()]);
    }
}