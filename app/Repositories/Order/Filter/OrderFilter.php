<?php

declare(strict_types=1);

namespace App\Repositories\Order\Filter;

use Illuminate\Database\Eloquent\Builder;
use function is_array;

final class OrderFilter implements OrderFilterInterface
{
    public function __construct(private readonly Builder $builder)
    {
    }

    public function filterId(int|string|array $id): void
    {
        if (is_array($id)) {
            $this->builder->whereIn('orders.id', $id);
        } else {
            $this->builder->where('orders.id', $id);
        }
    }

    public function filterManufacturerId(int|string $manufacturerId): void
    {
        $this->builder->where('orders.manufacturer_id', $manufacturerId);
    }

    public function filterSourceId(int|string $sourceId): void
    {
        $this->builder->where('orders.source_id', $sourceId);
    }

    public function filterSellerId(int|string $sellerId): void
    {
        $this->builder->where('orders.seller_id', $sellerId);
    }

    public function filterNumber(string $number): void
    {
        $this->builder->where('orders.number', $number);
    }

    public function filterUserId(int|string|array $userId): void
    {
        if (is_array($userId)) {
            $this->builder->whereIn('orders.user_id', $userId);
        } else {
            $this->builder->where('orders.user_id', $userId);
        }
    }

    public function filterStatus(string|array $status): void
    {
        if (is_array($status)) {
            $this->builder->whereIn('orders.status', $status);
        } else {
            $this->builder->where('orders.status', $status);
        }
    }

    public function filterDraft(bool $isDraft): void
    {
        $this->builder->where('orders.draft', $isDraft);
    }

    public function filterPhone(string $phone): void
    {
        $this->builder->where('orders.phone', $phone);
    }

    public function filterOrderDate(string $date): void
    {
        $this->builder->where('orders.order_date', $date);
    }

    public function filterAcceptedDateStart(string $date): void
    {
        $this->builder->where('orders.accepted_date', '>=', $date);
    }

    public function filterAcceptedDateEnd(string $date): void
    {
        $this->builder->where('orders.accepted_date', '<=', $date);
    }

    public function filterOrderDateStart(string $date): void
    {
        $this->builder->where('orders.order_date', '>=', $date);
    }

    public function filterOrderDateEnd(string $date): void
    {
        $this->builder->where('orders.order_date', '<=', $date);
    }

    public function filterCreatedAtStart(string $date): void
    {
        $this->builder->where('orders.created_at', '>=', $date);
    }

    public function filterCreatedAtEnd(string $date): void
    {
        $this->builder->where('orders.created_at', '<=', $date);
    }

    public function filterOnlyTrashed(): void
    {
        $this->builder->onlyTrashed();
    }

    public function filterWithTrashed(): void
    {
        $this->builder->withTrashed();
    }
}