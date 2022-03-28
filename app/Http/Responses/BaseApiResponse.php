<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait BaseApiResponse
{
    /**
     * @param array $data
     * @param string|null $message
     * @param int $code
     * @param array $headers
     * @return JsonResponse
     */
    protected function responseSuccess(
        array $data = [],
        string $message = null,
        int $code = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        $response = response()->json(
            $data,
            $code
        );

        $this->setHeaders($response, $headers);

        return $response;
    }

    /**
     * @param int $code
     * @param string|null $message
     * @param array $data
     * @param array $headers
     * @return JsonResponse
     */
    protected function responseError(
        int $code,
        string $message = null,
        array $data = [],
        array $headers = []
    ): JsonResponse {
        $response = response()->json(
            array_merge(
                [
                    'status' => 'Error',
                    'message' => $message,
                ],
                $data,
            ),
            $code
        );

        $this->setHeaders($response, $headers);

        return $response;
    }

    private function setHeaders(JsonResponse $response, array $headers): void
    {
        if (empty($headers)) {
            return;
        }

        foreach ($headers as $headerKey => $value) {
            $response->header($headerKey, $value);
        }
    }
}
