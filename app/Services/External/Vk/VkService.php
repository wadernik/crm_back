<?php

namespace App\Services\External\Vk;

class VkService
{
    private VkApiClient $vkApiClient;

    public function __construct()
    {
        $this->vkApiClient = new VkApiClient(
            config('vk.url'),
            config('vk.redirect_uri'),
            config('vk.client_id'),
            config('vk.client_secret'),
            config('vk.group_ids')
        );
    }

    /**
     * @return string
     */
    public function getUrlCode(): string
    {
        return $this->vkApiClient->getCodeUrl();
    }

    /**
     * @param string $code
     * @return string|null
     */
    public function getAccessToken(string $code): ?string
    {
        // TODO: think about expired_at
        $responseData = $this->vkApiClient->retrieveAccessToken($code);

        return collect($responseData['groups'])->first()['access_token'] ?? '';
    }
}
