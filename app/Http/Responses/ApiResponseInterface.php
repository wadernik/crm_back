<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

interface ApiResponseInterface
{
    public static function responseSuccess(
        array $data = [],
        int $code = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse;

    public static function responseError(
        int $code,
        string $message = null,
        array $data = [],
        array $headers = []
    ): JsonResponse;

    /**
     * @param string $filePath
     * @param string $fileName
     * @param bool $shouldDeleteAfterSend Delete file from temporary folder after download.
     * @param array $headers
     * @return BinaryFileResponse
     */
    public static function responseBinary(
        string $filePath,
        string $fileName,
        bool $shouldDeleteAfterSend = true,
        array $headers = []
    ): BinaryFileResponse;
}