<?php

declare(strict_types=1);

namespace App\Managers\Setting;

use App\DTOs\Setting\CreateSettingDTOInterface;
use App\Models\Setting\Setting;

final class SettingManager implements SettingManagerInterface
{
    public function create(CreateSettingDTOInterface $settingDTO): Setting
    {
        /** @var Setting $setting */
        $setting = Setting::query()->create($settingDTO->toArray());

        return $setting;
    }

    public function update(Setting $setting, CreateSettingDTOInterface $settingDTO): Setting
    {
        $setting->update($settingDTO->toArray());

        return $setting;
    }

    public function delete(Setting $setting): Setting
    {
        $setting->delete();

        return $setting;
    }
}