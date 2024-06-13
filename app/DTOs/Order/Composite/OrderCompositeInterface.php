<?php

declare(strict_types=1);

namespace App\DTOs\Order\Composite;

use App\Models\Comment\Comment;
use App\Models\Order\Contact\OrderContact;
use App\Models\Order\Item\OrderItem;
use App\Models\Order\Order;
use Illuminate\Contracts\Support\Arrayable;

interface OrderCompositeInterface extends Arrayable
{
    public function order(): Order;

    public function setOrder(Order $order): void;

    /**
     * @return array<OrderItem>
     */
    public function items(): array;

    public function setOrderItems(?OrderItem ...$items): void;

    public function contact(): OrderContact;

    public function setContact(?OrderContact $contact = null): void;

    /**
     * @return array<Comment>
     */
    public function comments(): array;

    public function setComments(Comment ...$comments): void;

    public function commentsTotal(): int;

    public function setCommentsTotal(int $commentsTotal = 0): void;
}