<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response as BinaryResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use function array_merge;
use function response;

final class ApiResponse implements ApiResponseInterface
{
    public static function responseSuccess(
        array $data = [],
        int $total = null,
        int $code = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        $dataResponse = $total === null
            ? $data
            : ['items' => $data, 'total' => $total];

        return response()->json(
            data: $dataResponse,
            status: $code,
            headers: $headers
        );
    }

    public static function responseError(
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

    public static function responseBinary(
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