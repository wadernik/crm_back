<?php

namespace App\Listeners;

use App\Events\Order\OrderOverdueEvent;
use App\Jobs\OrderOverdueNotificationJob;
use App\Models\User\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderOverdueEventListener implements ShouldQueue
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function handle(OrderOverdueEvent $event): void
    {
        foreach ($this->users() as $user) {
            OrderOverdueNotificationJob::dispatch($event->order(), $user, $event->timeout());
        }
    }

    /**
     * TODO: iterate by partitions
     * @return array<User>
     */
    private function users(): array
    {
        return $this->userRepository->findAllBy([])->all();
    }
}