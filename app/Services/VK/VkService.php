<?php

namespace App\Services\VK;

class VkService implements VkServiceInterface
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

    public function urlCode(): string
    {
        return $this->vkApiClient->getCodeUrl();
    }

    public function accessToken(string $code): ?string
    {
        // TODO: think about expired_at
        [$success, $responseData] = $this->vkApiClient->retrieveAccessToken($code);

        if (!$success) {
            return null;
        }

        return collect($responseData['groups'])->first()['access_token'] ?? '';
    }
}