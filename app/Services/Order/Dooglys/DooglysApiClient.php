<?php

declare(strict_types=1);

namespace App\Services\Order\Dooglys;

use App\Services\Order\Dooglys\Sub\DooglysResponse;
use App\Services\Order\Dooglys\Sub\DooglysResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

final class DooglysApiClient implements DooglysApiClientInterface
{
    private const ACTION_VIEW_ORDER = '/sales/order/view';
    private const ACTION_LIST_ORDERS = '/sales/order/list';

    public function __construct(private string $api, private string $token)
    {
    }

    public function orders(int|string $dateStart, int|string $dateEnd, string $orderNumber): DooglysResponseInterface
    {
        $requestParams = [
            'query' => [
                'date_created_from' => $dateStart,
                'date_created_to' => $dateEnd,
                // 'number' => $orderNumber,
            ],
        ];

        return $this->executeRequest('GET', self::ACTION_LIST_ORDERS, $requestParams);
    }

    private function executeRequest(string $method, string $action, array $requestParams = []): DooglysResponseInterface
    {
        if ($this->api === '' || $this->token === '') {
            return new DooglysResponse(false, []);
        }

        $httpClient = new Client();
        $url = $this->api . $action;

        $requestParams[RequestOptions::HEADERS]['Access-Token'] = $this->token;
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

        return new DooglysResponse($success, $response);
    }
}