<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Upload;

use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\Uploads\CreateFilesUploadRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Upload\UploadManagerInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UploadController extends AbstractApiController
{
    public function __invoke(CreateFilesUploadRequest $request, UploadManagerInterface $manager): JsonResponse
    {
        $uploaded = [];

        foreach ($manager->create(...collect($request->allFiles())->first()) as $file) {
            $uploaded[] = $file;
        }

        return ApiResponse::responseSuccess(data: $uploaded, code: Response::HTTP_CREATED);
    }
}