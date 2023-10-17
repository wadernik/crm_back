<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\Board\CreateBoardGroupEvent;

final class CreateBoardGroupEventListener
{
    public function handle(CreateBoardGroupEvent $event): void
    {
    }
}