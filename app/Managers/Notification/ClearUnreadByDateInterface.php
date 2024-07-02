<?php

declare(strict_types=1);

namespace App\Managers\Notification;

interface ClearUnreadByDateInterface
{
    public function clearUnreadByDate(string $date): void;
}