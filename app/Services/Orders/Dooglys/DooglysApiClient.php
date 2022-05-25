<?php

namespace App\Services\Orders\Dooglys;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DooglysApiClient
{
    public const ACTION_VIEW_ORDER = '/sales/order/view';
    public const ACTION_LIST_ORDERS = '/sales/order/list';

    private string $apiUrl;
    private string $accessToken;

    public function init(string $apiUrl, string $accessToken): void
    {
        $this->apiUrl = $apiUrl;
        $this->accessToken = $accessToken;
    }

    /**
     * @param int|string $dateStartTimestamp
     * @param int|string $dateEndTimestamp
     * @param string $orderNumber
     * @return array
     */
    public function listOrders(int|string $dateStartTimestamp, int|string $dateEndTimestamp, string $orderNumber): array
    {
        $requestParams = [
            'query' => [
                'date_created_from' => $dateStartTimestamp,
                'date_created_to' => $dateEndTimestamp,
                'number' => $orderNumber,
            ],
        ];

        return $this->executeRequest('GET', self::ACTION_LIST_ORDERS, $requestParams);
    }

    /**
     * @param string $method
     * @param string $action
     * @param array $requestParams
     * @return array [$success, $response]
     */
    private function executeRequest(string $method, string $action, array $requestParams = []): array
    {
        if ($this->accessToken === '' || $this->apiUrl === '') {
            return [false, []];
        }

        $httpClient = new Client();
        $url = $this->apiUrl . $action;

        $requestParams[RequestOptions::HEADERS]['Access-Token'] = $this->accessToken;
        $requestParams[RequestOptions::HEADERS]['Content-Type'] = 'application/json; charset=utf-8';
        $requestParams[RequestOptions::HEADERS]['Accept'] = 'application/json';

        $success = false;
        $response = [];

        try {
            $response = $httpClient
                ->$method($url, $requestParams)
                ->getBody()
                ->getContents();

            $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
            $success = true;
        } catch (ConnectException | BadRequestException | BadResponseException $e) {
            $code = $e->getResponse()->getStatusCode();

            Log::error("Dooglys API Client ERROR: Code [$code]");
            Log::error($e->getMessage());
        } catch (\JsonException $e) {
            Log::error("Dooglys API Client ERROR: unable to decode response");
            Log::info($e->getMessage());
        }

        return [$success, $response];
    }
}
