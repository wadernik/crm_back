<?php

declare(strict_types=1);

namespace App\Services\Setting\ManagerExtension;

use App\DTOs\Setting\CreateSettingDTOInterface;
use App\Managers\Setting\SettingManagerInterface;
use App\Models\Setting\Setting;
use App\Repositories\Setting\SettingRepositoryInterface;

final class SettingCreatorService implements SettingCreatorServiceInterface
{
    public function __construct(
        private readonly SettingRepositoryInterface $repository,
        private readonly SettingManagerInterface $manager
    )
    {
    }

    public function create(CreateSettingDTOInterface $settingDTO): Setting
    {
        $setting = $this->repository->findOneByTypeId($settingDTO->toArray()['type_id']);

        if (!$setting) {
            $setting = $this->manager->create($settingDTO);
        }

        return $setting;
    }
}