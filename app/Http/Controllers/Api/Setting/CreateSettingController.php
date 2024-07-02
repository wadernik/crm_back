<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Setting;

use App\Attributes\Permission;
use App\DTOs\Setting\CreateSettingDTO;
use App\Http\Controllers\Api\AbstractApiController;
use App\Http\Requests\OrderSetting\CreateSettingRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Setting\ManagerExtension\SettingCreatorServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Permission('settings.edit')]
final class CreateSettingController extends AbstractApiController
{
    public function __invoke(
        CreateSettingRequest $request,
        SettingCreatorServiceInterface $manager
    ): JsonResponse
    {
        $dto = new CreateSettingDTO($request->validated());

        if (!$setting = $manager->create($dto)) {
            return ApiResponse::responseError(Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::responseSuccess($setting->toArray());
    }
}