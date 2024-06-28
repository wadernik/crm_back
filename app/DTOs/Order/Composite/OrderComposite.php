<?php

declare(strict_types=1);

namespace App\DTOs\Order\Composite;

use App\DTOs\Order\OrderDTOInterface;
use App\Models\Comment\Comment;
use App\Models\Order\Contact\OrderContact;
use App\Models\Order\Item\OrderItem;
use App\Models\Order\Order;
use function array_map;
use function collect;

final class OrderComposite implements OrderCompositeInterface
{
    private Order $order;

    /**
     * @var array<OrderItem>
     */
    private array $orderItems = [];

    private ?OrderContact $contact = null;

    /**
     * @var array<Comment>
     */
    private array $comments = [];

    private int $commentsTotal = 0;

    private int $filesTotal = 0;

    public function __construct(private readonly ?OrderDTOInterface $orderDTO = null)
    {
        if (!$this->orderDTO) {
            return;
        }

        $this->order = new Order($this->orderDTO->main());

        if (isset($this->orderDTO->main()['id'])) {
            $this->order->id = $this->orderDTO->main()['id'];
        }

        foreach ($this->orderDTO->items() as $item) {
            $orderItem = new OrderItem($item);

            if (isset($item['id'])) {
                $orderItem->id = $item['id'];
            }

            if (!empty($item['files'])) {
                $fileIds = collect($item['files'])
                    ->pluck('id')
                    ->toArray();

                $orderItem->setFilesCollection($fileIds);
            }

            $this->orderItems[] = $orderItem;
        }

        $this->contact = new OrderContact($this->orderDTO->contact());
    }

    public function order(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    public function items(): array
    {
        return $this->orderItems;
    }

    public function setOrderItems(?OrderItem ...$items): void
    {
        $this->orderItems = $items;
    }

    public function contact(): OrderContact
    {
        return $this->contact;
    }

    public function setContact(?OrderContact $contact = null): void
    {
        $this->contact = $contact;
    }

    public function comments(): array
    {
        return $this->comments;
    }

    public function setComments(Comment ...$comments): void
    {
        $this->comments = $comments;
    }

    public function commentsTotal(): int
    {
        return $this->commentsTotal;
    }

    public function setCommentsTotal(int $commentsTotal = 0): void
    {
        $this->commentsTotal = $commentsTotal;
    }

    public function filesTotal(): int
    {
        return $this->filesTotal;
    }

    public function setFilesTotal(int $filesTotal = 0): void
    {
        $this->filesTotal = $filesTotal;
    }

    public function toArray(): array
    {
        $order = $this->order->toArray();

        $order['items'] = array_map(
            static fn(OrderItem $item): array => $item->toArray(),
            $this->items()
        );

        $order['contact'] = $this->contact?->toArray();

        $order['comments'] = array_map(
            static fn (Comment $comment): array => $comment->toArray(),
            $this->comments
        );

        $order['total_comments'] = $this->commentsTotal;

        $order['total_files'] = $this->filesTotal;

        return $order;
    }
}