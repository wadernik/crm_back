<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Import;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Jobs\ImportSellersJob;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SellerImportController extends AbstractApiController
{
    public function __invoke(): JsonResponse
    {
        if (!$this->isAllowed('sellers.import')) {
            return ApiResponse::responseError(Response::HTTP_FORBIDDEN);
        }

        ImportSellersJob::dispatch();

        return ApiResponse::responseSuccess();
    }
}