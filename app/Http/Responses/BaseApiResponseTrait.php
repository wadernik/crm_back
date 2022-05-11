<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response as BinaryResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

trait BaseApiResponseTrait
{
    /**
     * @param array $data
     * @param int $code
     * @param array $headers
     * @return JsonResponse
     */
    protected function responseSuccess(
        array $data = [],
        int $code = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return response()->json(
            data: $data,
            status: $code,
            headers: $headers
        );
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
        return response()->json(
            data: array_merge(
                [
                    'status' => 'Error',
                    'message' => $message,
                ],
                $data,
            ),
            status: $code,
            headers: $headers
        );
    }

    /**
     * @param string $filePath
     * @param string $fileName
     * @param bool $shouldDeleteAfterSend Delete file from temporary folder after download.
     * @param array $headers
     * @return BinaryFileResponse
     */
    protected function responseBinary(
        string $filePath,
        string $fileName,
        bool $shouldDeleteAfterSend = true,
        array $headers = []
    ): BinaryFileResponse {
        $headers = array_merge(
            $headers,
            ['Access-Control-Expose-Headers' => "Content-Disposition,X-Suggested-Filename"]
        );

        return BinaryResponse::download(file: $filePath, name: $fileName, headers: $headers)
            ->deleteFileAfterSend($shouldDeleteAfterSend);
    }
}
