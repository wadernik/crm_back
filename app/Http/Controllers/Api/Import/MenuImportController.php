<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Import;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Jobs\ImportMenuJob;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class MenuImportController extends AbstractApiController
{
    public function __invoke(): JsonResponse
    {
        if (!$this->isAllowed('menu.import')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        ImportMenuJob::dispatch();

        return ApiResponse::responseSuccess();
    }
}