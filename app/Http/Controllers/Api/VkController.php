<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OAuth\Vk\VkGetAccessTokenRequest;
use App\Http\Requests\OAuth\Vk\VkGetCodeRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class VkController extends AbstractBaseApiController
{
    public function authorizeApp(): Response
    {
        return response('', 200);
    }

    public function getCode(VkGetCodeRequest $request): Response
    {
        Log::info($request->validated());

        return response('', 200);
    }

    public function getAccessToken(VkGetAccessTokenRequest $request): Response
    {
        Log::info($request->validated());

        return response('', 200);
    }
}
