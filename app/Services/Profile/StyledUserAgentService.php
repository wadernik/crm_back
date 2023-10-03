<?php

declare(strict_types=1);

namespace App\Services\Profile;

use Jenssegers\Agent\Agent;
use function collect;

final class StyledUserAgentService implements StyledUserAgentServiceInterface
{
    public function agent(string $userAgent): string
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