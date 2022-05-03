<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Uploads\CreateFilesUploadRequest;
use App\Http\Requests\Uploads\CreateFileUploadRequest;
use App\Services\FilesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FilesController extends BaseApiController
{
    public function __construct(private FilesService $filesService)
    {}

    /**
     * @param CreateFilesUploadRequest $request
     * @return JsonResponse
     */
    public function upload(CreateFilesUploadRequest $request): JsonResponse
    {
        if (!$this->isAllowed('files.edit')) {
            return $this->responseError(code: Response::HTTP_FORBIDDEN);
        }

        $files = $request->allFiles();

        if (!$files) {
            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $uploadedFiles = [];
            foreach ($files['files'] as $file) {
                /** @var UploadedFile */
                $uploadedFile = $file;

                $uploadedFiles[] = $this->filesService->uploadFile($uploadedFile);
            }

            return $this->responseSuccess(data: $uploadedFiles, code: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @TODO implement file download api method
     */
    public function download(): void
    {
        // if (!$this->isAllowed('files.view')) {
        //     return $this->responseError(code: Response::HTTP_FORBIDDEN);
        // }
    }
}
