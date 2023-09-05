<?php

declare(strict_types=1);

namespace App\Repositories\Notification;

interface NotificationRepositoryInterface extends FindAllByUserInterface, FindOneByUserAndIdInterface
{
}