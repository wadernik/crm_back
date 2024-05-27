<?php

declare(strict_types=1);

namespace App\Services\Order\Enricher;

use App\DTOs\Order\Composite\OrderCompositeInterface;
use App\Models\Order\Order;
use App\Repositories\Comment\CommentRepositoryInterface;

final class OrderCompositeByCommentsEnricher implements OrderCompositeByCommentsEnricherInterface
{
    public function __construct(private readonly CommentRepositoryInterface $commentRepository)
    {
    }

    public function enrich(OrderCompositeInterface $composite): OrderCompositeInterface
    {
        $this->setTotalComments($composite);

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
        foreach ($composites as $composite) {
            $this->setTotalComments($composite);
        }

        return $composites;
    }

    private function setTotalComments(OrderCompositeInterface $composite): void
    {
        $totalComments = $this->commentRepository->count([
            'filter' => [
                'commentable_type' => Order::class,
                'commentable_id' => $composite->order()->id
            ]
        ]);

        $composite->setCommentsTotal($totalComments);
    }
}