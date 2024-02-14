<?php

namespace App\Listeners;

use App\Events\Order\OrderOverdueEvent;
use App\Models\User\User;
use App\Notifications\OrderOverdueNotification;
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
            $user->notify(new OrderOverdueNotification($event->order()));
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