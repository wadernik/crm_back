<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Setting;

use App\Attributes\Permission;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Responses\ApiResponse;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('settings.view')]
final class GetSettingController extends AbstractApiController
{
    public function __invoke(int $id, SettingRepositoryInterface $repository): JsonResponse
    {
        if (!$setting = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($setting->toArray());
    }
}