<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Uploads\CreateFilesUploadRequest;
use App\Services\FilesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FilesController extends AbstractBaseApiController
{
    /**
     * @param CreateFilesUploadRequest $request
     * @param FilesService $filesService
     * @return JsonResponse
     */
    public function upload(CreateFilesUploadRequest $request, FilesService $filesService): JsonResponse
    {
        // if (!$this->isAllowed('files.edit')) {
        //     return $this->responseError(code: Response::HTTP_FORBIDDEN);
        // }

        $files = $request->allFiles();

        if (!$files) {
            return $this->responseError(code: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $uploadedFiles = [];
            foreach ($files['files'] as $file) {
                /** @var UploadedFile */
                $uploadedFile = $file;

                $uploadedFiles[] = $filesService->uploadFile($uploadedFile);
            }

            return $this->responseSuccess(data: $uploadedFiles, code: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return $this->responseError(code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
