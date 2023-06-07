<?php

declare(strict_types=1);

namespace App\DTOs\Order;

use App\DTOs\Sub\ImmutableNameInterface;
use App\DTOs\Sub\LabelInterface;
use Illuminate\Contracts\Support\Arrayable;

interface CreateOrderDTOInterface extends ImmutableNameInterface, LabelInterface, Arrayable
{
    public function amount(): string;

    public function comment(): ?string;

    public function decoration(): ?string;

    public function acceptedDate(): string;

    public function orderDate(): string;

    public function orderTime(): string;

    public function numberExternal(): ?string;

    public function manufacturerId(): int;

    public function sourceId(): int;

    public function sellerId(): int;

    public function userId(): int;

    public function files(): ?array;
}