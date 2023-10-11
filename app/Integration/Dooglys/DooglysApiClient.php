<?php

declare(strict_types=1);

namespace App\Integration\Dooglys;

use App\Integration\Dooglys\Response\DooglysResponse;
use App\Integration\Dooglys\Response\DooglysResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use function json_decode;

final class DooglysApiClient implements DooglysApiClientInterface
{
    private string $action;
    private string $method = 'get';
    private array $params = [];

    public function __construct(private readonly string $api, private readonly string $token)
    {
    }

    public function request(string $action): DooglysApiClientInterface
    {
        $this->action = $action;

        return $this;
    }

    public function method(string $method): DooglysApiClientInterface
    {
        $this->method = $method;

        return $this;
    }

    public function params(array $params): DooglysApiClientInterface
    {
        $this->params = $params;

        return $this;
    }

    public function execute(): DooglysResponseInterface
    {
        if ($this->api === '' || $this->token === '') {
            return new DooglysResponse(false, []);
        }

        $client = new Client();

        $this->params[RequestOptions::HEADERS]['Access-Token'] = $this->token;
        $this->params[RequestOptions::HEADERS]['Content-Type'] = 'application/json; charset=utf-8';
        $this->params[RequestOptions::HEADERS]['Accept'] = 'application/json';
        $this->params[RequestOptions::VERIFY] = false;
        $this->params[RequestOptions::CONNECT_TIMEOUT] = 30.0;

        $responseStatus = false;
        $responseData = [];

        $url = "{$this->api}{$this->action}";

        try {
            $response = $client->request($this->method, $url, $this->params);

            $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            $responseStatus = true;
        } catch (ConnectException | BadRequestException | BadResponseException | GuzzleException $e) {
            $code = $e->getResponse()->getStatusCode();

            Log::error("Dooglys API Client ERROR: Code [$code]");
            Log::error($e->getMessage());
        } catch (\JsonException $e) {
            Log::error("Dooglys API Client ERROR: unable to decode response");
            Log::info($e->getMessage());
        }

        return new DooglysResponse($responseStatus, $responseData);
    }
}