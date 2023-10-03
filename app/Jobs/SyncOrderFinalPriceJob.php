<?php

namespace App\Jobs;

use App\DTOs\Order\UpdateOrderDTO;
use App\Managers\Order\Normal\OrderManagerInterface;
use App\Models\Order\Order;
use App\Services\Order\Processor\OrderFinalPriceProcessorInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SyncOrderFinalPriceJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 30;

    public function __construct(private readonly Order $order)
    {
    }

    /**
     * @throws Throwable
     */
    public function handle(
        OrderFinalPriceProcessorInterface $finalPriceProcessor,
        OrderManagerInterface $orderManager
    ): void
    {
        if (!$price = $finalPriceProcessor->run($this->order)) {
            $this->release(now()->addMinutes(config('dooglys.retry_timeout')));

            return;
        }

        $orderManager->update($this->order, new UpdateOrderDTO(['price' => $price]));
    }

    public function uniqueId(): string
    {
        return (string) $this->order->id;
    }

    /**
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->order->id))->expireAfter(180)];
    }
}