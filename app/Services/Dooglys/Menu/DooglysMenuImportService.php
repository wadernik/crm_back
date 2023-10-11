<?php

declare(strict_types=1);

namespace App\Services\Dooglys\Menu;

use App\Jobs\ProcessImportMenuJob;
use App\Repositories\Seller\SellerRepositoryInterface;
use App\Services\Dooglys\Sub\DooglysMenuActionInterface;
use function App\Helpers\Functions\load_service;

final class DooglysMenuImportService implements DooglysMenuImportServiceInterface
{
    private readonly DooglysMenuActionInterface $dooglysMenuAction;
    private readonly SellerRepositoryInterface $sellerRepository;

    public function __construct()
    {
        $this->dooglysMenuAction = load_service(DooglysMenuActionInterface::class);
        $this->sellerRepository = load_service(SellerRepositoryInterface::class);
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