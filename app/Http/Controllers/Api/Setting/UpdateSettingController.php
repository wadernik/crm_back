<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Setting;

use App\Attributes\Permission;
use App\DTOs\Setting\UpdateSettingDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\OrderSetting\UpdateSettingRequest;
use App\Http\Responses\ApiResponse;
use App\Managers\Setting\SettingManagerInterface;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('settings.edit')]
final class UpdateSettingController extends AbstractApiController
{
    public function __invoke(
        int $id,
        UpdateSettingRequest $request,
        SettingRepositoryInterface $repository,
        SettingManagerInterface $manager
    ): JsonResponse
    {
        $dto = new UpdateSettingDTO($request->validated());

        if (!$setting = $repository->find($id)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        $setting = $manager->update($setting, $dto);

        return ApiResponse::responseSuccess($setting->toArray());
    }
}