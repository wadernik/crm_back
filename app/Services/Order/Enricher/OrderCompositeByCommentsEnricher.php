<?php

declare(strict_types=1);

namespace App\Services\Order\Enricher;

use App\DTOs\Order\Composite\OrderCompositeInterface;
use App\Models\Order\Order;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Order\File\OrderFileRepositoryInterface;
use function array_chunk;
use function array_map;

final class OrderCompositeByCommentsEnricher implements OrderCompositeByCommentsEnricherInterface
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly OrderFileRepositoryInterface $orderFileRepository
    )
    {
    }

    public function enrich(OrderCompositeInterface $composite): OrderCompositeInterface
    {
        $totalComments = $this->commentRepository->count([
            'filter' => [
                'commentable_type' => Order::class,
                'commentable_id' => $composite->order()->id
            ]
        ]);

        $composite->setCommentsTotal($totalComments);

        $comments = $this->commentRepository->findAllBy(
            criteria: [
                'filter' => [
                    'commentable_type' => Order::class,
                    'commentable_id' => $composite->order()->id
                ]
            ],
            sort: ['sort' => 'created_at']
        );

        $composite->setComments(...$comments);

        return $composite;
    }

    public function enrichCollection(OrderCompositeInterface ...$composites): array
    {
        $totalComments = $this->aggregateComments(...$composites);

        $totalFiles = $this->aggregateFiles(...$composites);

        foreach ($composites as $composite) {
            $composite->setCommentsTotal($totalComments[$composite->order()->id]['amount'] ?? 0);

            foreach ($composite->items() as $item) {
                $composite->setFilesTotal($totalFiles[$item->id]['amount'] ?? 0);
            }
        }

        return $composites;
    }

    private function aggregateComments(OrderCompositeInterface ...$composites): array
    {
        $orderIds = array_map(
            static fn(OrderCompositeInterface $orderComposite): int => $orderComposite->order()->id,
            $composites
        );

        $orderIds = array_chunk($orderIds, 100);

        $totalCommentBatches = [];

        foreach ($orderIds as $chunk) {
            $totalCommentBatches[] = $this->commentRepository->aggregateByCommentableIds(Order::class, $chunk);
        }

        $totalComments = [];

        foreach ($totalCommentBatches as $batch) {
            foreach ($batch as $orderId => $comment) {
                $totalComments[$orderId] = $comment->toArray();
            }
        }

        return $totalComments;
    }

    private function aggregateFiles(OrderCompositeInterface ...$composites): array
    {
        $itemIds = [];

        foreach ($composites as $composite) {
            foreach ($composite->items() as $item) {
                $itemIds[$item->id] = $item->id;
            }
        }

        return $this->orderFileRepository->aggregateByOrderItemIds($itemIds)->toArray();
    }
}