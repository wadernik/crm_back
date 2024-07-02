<?php

declare(strict_types=1);

namespace App\Managers\Setting;

interface SettingManagerInterface extends SettingCreatorInterface,
                                          SettingUpdaterInterface,
                                          SettingDeleterInterface
{
}