<?php

declare(strict_types=1);

namespace App\DTOs\Order;

use App\DTOs\Sub\LabelInterface;
use App\DTOs\Sub\NameInterface;
use Illuminate\Contracts\Support\Arrayable;

interface UpdateOrderDTOInterface extends NameInterface, LabelInterface, Arrayable
{
    public function amount(): ?string;

    public function comment(): ?string;

    public function decoration(): ?string;

    public function acceptedDate(): ?string;

    public function orderDate(): ?string;

    public function orderTime(): ?string;

    public function numberExternal(): ?string;

    public function manufacturerId(): ?int;

    public function sourceId(): ?int;

    public function sellerId(): ?int;

    public function files(): ?array;

    public function orderId(): ?int;

    public function status(): ?int;
}