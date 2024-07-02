<?php

declare(strict_types=1);

namespace App\Managers\Notification;

interface ClearReadByDateInterface
{
    public function clearReadByDate(string $date): void;
}