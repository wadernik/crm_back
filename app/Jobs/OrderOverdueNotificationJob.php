<?php

namespace App\Jobs;

use App\Models\Order\Order;
use App\Models\User\User;
use App\Notifications\OrderOverdueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Queue\SerializesModels;
use Throwable;
use function now;

class OrderOverdueNotificationJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public function __construct(
        private readonly Order $order,
        private readonly User $user,
        private readonly int $timeout = 18000
    )
    {
    }

    public function handle(): void
    {
        $this->user->notify(new OrderOverdueNotification($this->order));

        $this->release(now()->addSeconds($this->timeout));
    }

    public function failed(?Throwable $exception): void
    {
        if ($exception instanceof MaxAttemptsExceededException) {
            $this->delete();
        }
    }

    public function uniqueId(): string
    {
        return $this->order->id . '-' . $this->user->id;
    }
}