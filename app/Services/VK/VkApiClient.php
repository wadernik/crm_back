<?php

namespace App\Services\VK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class VkApiClient implements VkApiClientInterface
{
    public const ACTION_GET_CODE = '/authorize'; // GET
    public const ACTION_GET_ACCESS_TOKEN = '/access_token'; // GET

    private const RESPONSE_TYPE_CODE = 'code';
    private const RESPONSE_TYPE_ACCESS_TOKEN = 'access_token';
    private const SCOPE_MESSAGES = 'messages';

    public function __construct(
        private string $url,
        private string $redirectUri,
        private string $clientId,
        private string $clientSecret,
        private string $groupIds
    )
    {
    }

    /**
     * @return string
     */
    public function codeUrl(): string
    {
        $requestParams = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'group_ids' => $this->groupIds,
            'response_type' => self::RESPONSE_TYPE_CODE,
            'scope' => self::SCOPE_MESSAGES,
        ];

        return $this->url . self::ACTION_GET_CODE . '?' . http_build_query($requestParams);
    }

    public function accessToken(string $code): array
    {
        $requestParams = [
            'query' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'response_type' => self::RESPONSE_TYPE_CODE,
                'code' => $code,
            ],
        ];

        return $this->executeRequest('GET', self::ACTION_GET_ACCESS_TOKEN, $requestParams);
    }

    /**
     * @param string $method
     * @param string $action
     * @param array $requestParams
     * @return array
     */
    private function executeRequest(string $method, string $action, array $requestParams = []): array
    {
        if ($this->url === '' || $this->redirectUri === '') {
            return [false, []];
        }

        $httpClient = new Client();
        $desiredUrl = $this->url . $action;

        $success = false;
        $response = [];

        try {
            $response = $httpClient
                ->$method($desiredUrl, $requestParams)
                ->getBody()
                ->getContents();

            $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
            $success = true;
        } catch (ConnectException | BadRequestException | BadResponseException $e) {
            $code = $e->getResponse()->getStatusCode();

            Log::error("VK Client ERROR: Code [$code]");
            Log::error($e->getMessage());
        } catch (\JsonException $e) {
            Log::error("VK Client ERROR: unable to decode response");
            Log::info($e->getMessage());
        }

        return [$success, $response];
    }
}