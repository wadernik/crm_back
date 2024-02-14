<?php

namespace App\Listeners;

use App\Events\Order\OrderEntityEvent;
use App\Models\User\User;
use App\Notifications\OrderEntityNotification;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderEntityEventListener implements ShouldQueue
{
    private UserRepositoryInterface $userRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function handle(OrderEntityEvent $event): void
    {
        foreach ($this->users() as $user) {
            if ($event->order()->user_id === $user->id) {
                continue;
            }

            $user->notify(new OrderEntityNotification($event->order(), $event->eventType()));
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