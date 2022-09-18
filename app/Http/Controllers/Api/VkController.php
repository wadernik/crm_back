<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OAuth\Vk\VkCatchRedirectRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class VkController extends AbstractBaseApiController
{
    public function authorizeApp(): Response
    {
        return response('', 200);
    }

    public function catchRedirect(VkCatchRedirectRequest $request): Response
    {
        Log::info($request->validated());

        return response('', 200);
    }
}
