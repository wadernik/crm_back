<?php

declare(strict_types=1);

namespace App\Managers\Notification;

interface DatabaseNotificationManagerInterface extends ClearUnreadByDateInterface, ClearReadByDateInterface
{
}