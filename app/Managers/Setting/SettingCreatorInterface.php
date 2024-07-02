<?php

declare(strict_types=1);

namespace App\Managers\Setting;

use App\DTOs\Setting\CreateSettingDTOInterface;
use App\Models\Setting\Setting;

interface SettingCreatorInterface
{
    public function create(CreateSettingDTOInterface $settingDTO): Setting;
}