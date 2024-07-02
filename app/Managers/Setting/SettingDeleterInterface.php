<?php

declare(strict_types=1);

namespace App\Managers\Setting;

use App\Models\Setting\Setting;

interface SettingDeleterInterface
{
    public function delete(Setting $setting): Setting;
}