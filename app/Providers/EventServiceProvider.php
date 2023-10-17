<?php

namespace App\Providers;

use App\Events\Board\CreateBoardGroupEvent;
use App\Events\Order\OrderEntityEvent;
use App\Listeners\CreateBoardGroupEventListener;
use App\Listeners\OrderEntityEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderEntityEvent::class => [OrderEntityEventListener::class],
        CreateBoardGroupEvent::class => [CreateBoardGroupEventListener::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}