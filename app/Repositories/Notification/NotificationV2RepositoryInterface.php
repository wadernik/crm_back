<?php

declare(strict_types=1);

namespace App\Repositories\Notification;

interface NotificationV2RepositoryInterface extends FindAllUnreadByUserWithLimitAndOffsetInterface,
                                                    FindOneByUserAndIdInterface,
                                                    CountUnreadByUserInterface
{
}