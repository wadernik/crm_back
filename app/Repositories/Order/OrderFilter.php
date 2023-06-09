<?php

declare(strict_types=1);

namespace App\Repositories\Order;

use Illuminate\Database\Eloquent\Builder;

final class OrderFilter implements OrderFilterInterface
{
    public function __construct(private Builder $builder)
    {
    }

    public function filterId(string|array $id): void
    {
        if (is_array($id)) {
            $this->builder->whereIn('orders.id', $id);
        } else {
            $this->builder->where('orders.id', $id);
        }
    }

    public function filterManufacturerId(string $manufacturerId): void
    {
        $this->builder->where('orders.manufacturer_id', $manufacturerId);
    }

    public function filterSourceId(string $sourceId): void
    {
        $this->builder->where('orders.source_id', $sourceId);
    }

    public function filterSellerId(string $sellerId): void
    {
        $this->builder->where('orders.seller_id', $sellerId);
    }

    public function filterUserId(string|array $userId): void
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

    public function filterName(string $name): void
    {
        $this->builder->where('order_details.name', 'like', '%' . $name . '%');
    }

    public function filterOnlyTrashed(): void
    {
        $this->builder->onlyTrashed();
    }
}