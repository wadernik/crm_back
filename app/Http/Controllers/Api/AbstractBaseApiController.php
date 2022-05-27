<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseApiResponseTrait;
use Jenssegers\Agent\Agent;

abstract class AbstractBaseApiController extends Controller
{
    use BaseApiResponseTrait;

    /**
     * @param string $permission
     * @return bool
     */
    protected function isAllowed(string $permission): bool
    {
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

        $device = $agent->device() ?: 'Desktop';

        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);

        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        $platformAsString = $platform . ($platformVersion ? " $platformVersion" : '');

        return collect([$device, "$browser $browserVersion", $platformAsString])->join('; ');
    }
}
