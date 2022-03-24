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
     * @return JsonResponse
     */
    protected function responseSuccess(
        array $data = [],
        string $message = null,
        int $code = Response::HTTP_OK
    ): JsonResponse {
        return response()->json(
            array_merge(
                [
                    'status' => 'Success',
                    'message' => $message,
                ],
                $data,
            ),
            $code
        );
    }

    /**
     * @param int $code
     * @param string|null $message
     * @param array $data
     * @return JsonResponse
     */
    protected function responseError(int $code, string $message = null, array $data = []): JsonResponse
    {
        return response()->json(
            array_merge(
                [
                    'status' => 'Error',
                    'message' => $message,
                ],
                $data,
            ),
            $code
        );
    }
}
