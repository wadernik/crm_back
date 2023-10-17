<?php

declare(strict_types=1);

namespace App\Events\Board;

use App\Models\Board\Group;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class CreateBoardGroupEvent implements GroupableInterface, ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(private readonly Group $group)
    {
    }

    public function group(): Group
    {
        return $this->group;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("boards.{$this->group->board_id}"),
        ];
    }
}