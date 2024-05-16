<?php

declare(strict_types=1);

namespace App\Services\Order\ManagerExtension\Normal;

use App\Exceptions\NotSuitableSellerException;
use App\Models\Order\Order;
use App\Repositories\Order\OrderDraftRepositoryInterface;
use App\Services\Order\Checker\OrderSellerCheckerInterface;
use App\Services\Order\ManagerExtension\BaseOrderCreatorServiceInterface;
use function __;

final class OrderCreatorService implements OrderCreatorServiceInterface
{
    public function __construct(
        private readonly BaseOrderCreatorServiceInterface $innerService,
        private readonly OrderSellerCheckerInterface $orderSellerChecker,
        private readonly OrderDraftRepositoryInterface $draftRepository
    )
    {
    }

    public function create(array $attributes): Order
    {
        if (!$this->orderSellerChecker->check($attributes['seller_id'])) {
            throw new NotSuitableSellerException(message: __('order.not_suitable_seller'));
        }

        if (isset($attributes['draft_id'])) {
            $draft = $this->draftRepository->findIncludingTrashed($attributes['draft_id']);

            $attributes['number'] = $draft?->number;

            unset($attributes['draft_id']);
        }

        return $this->innerService->create($attributes);
    }
}