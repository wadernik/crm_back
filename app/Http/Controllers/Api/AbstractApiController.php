<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Jenssegers\Agent\Agent;

abstract class AbstractApiController extends Controller
{
    /**
     * @param string $permission
     * @return bool
     */
    protected function isAllowed(string $permission): bool
    {
        /** @var User $user */
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }

        return collect($user->getUserPermissions())->contains($permission);
    }

    /**
     * @param string $userAgent
     * @return string
     */
    protected function getStyledUserAgent(string $userAgent): string
    {
        $agent = new Agent();
        $agent->setHttpHeaders($userAgent);

        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);

        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        $platformAsString = $platform . ($platformVersion ? " $platformVersion" : '');

        return collect([$platformAsString, "$browser $browserVersion"])->join('; ');
    }
}