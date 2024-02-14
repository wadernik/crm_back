<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Menu;

use App\Jobs\ProcessImportMenuJob;
use App\Repositories\Seller\SellerRepositoryInterface;
use App\Services\Dooglys\Sub\DooglysMenuActionInterface;

final class DooglysMenuImportService implements DooglysMenuImportServiceInterface
{
    public function __construct(
        private readonly DooglysMenuActionInterface $dooglysMenuAction,
        private readonly SellerRepositoryInterface $sellerRepository
    )
    {
    }

    public function import(): void
    {
        $sellers = $this->sellerRepository->findAllBy(['filter' => ['uuid_not_null' => true]]);

        $menuIds = $sellers
            ->whereNotNull('menu_id')
            ->pluck('menu_id')
            ->unique()
            ->toArray();

        foreach ($menuIds as $menuId) {
            $menu = $this->dooglysMenuAction->menu($menuId);

            if (!empty($menu['products'])) {
                ProcessImportMenuJob::dispatch($menu['products'], $menuId);
            }
        }
    }
}