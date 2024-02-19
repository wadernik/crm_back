<?php

declare(strict_types=1);

namespace App\Repositories\Order\Filter;

interface OrderFilterInterface
{
    /**
     * @param string|array<string> $id
     * @return void
     */
    public function filterId(string|array $id): void;

    public function filterManufacturerId(string $manufacturerId): void;

    public function filterSourceId(string $sourceId): void;

    public function filterSellerId(string $sellerId): void;

    public function filterNumber(string $number): void;

    /**
     * @param string|array<string> $userId
     * @return void
     */
    public function filterUserId(string|array $userId): void;

    /**
     * @param string|array<string> $status
     * @return void
     */
    public function filterStatus(string|array $status): void;

    public function filterDraft(bool $isDraft): void;

    public function filterPhone(string $phone): void;

    public function filterOrderDate(string $date): void;

    public function filterAcceptedDate(string $date): void;

    public function filterAcceptedDateStart(string $date): void;

    public function filterAcceptedDateEnd(string $date): void;

    public function filterOrderDateStart(string $date): void;

    public function filterOrderDateEnd(string $date): void;

    public function filterCreatedAtStart(string $date): void;

    public function filterCreatedAtEnd(string $date): void;

    public function filterOnlyTrashed(): void;
}