<?php

declare(strict_types=1);

namespace App\Managers\Setting;

use App\DTOs\Setting\CreateSettingDTOInterface;
use App\Models\Setting\Setting;

interface SettingUpdaterInterface
{
    public function update(Setting $setting, CreateSettingDTOInterface $settingDTO): Setting;
}