<?php

namespace App\Providers;

use App\Factory\Profile\ProfileFactory;
use App\Factory\Profile\ProfileFactoryInterface;
use App\Managers\Manufacturer\ManufacturerManager;
use App\Managers\Manufacturer\ManufacturerManagerInterface;
use App\Managers\ManufacturerDateLimit\DateLimitManager;
use App\Managers\ManufacturerDateLimit\DateLimitManagerInterface;
use App\Managers\Order\Draft\OrderDraftManager;
use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Managers\Order\OrderManager;
use App\Managers\Order\OrderManagerInterface;
use App\Managers\Role\RoleManager;
use App\Managers\Role\RoleManagerInterface;
use App\Managers\Seller\SellerManager;
use App\Managers\Seller\SellerManagerInterface;
use App\Managers\Upload\UploadManager;
use App\Managers\Upload\UploadManagerInterface;
use App\Managers\User\UserManager;
use App\Managers\User\UserManagerInterface;
use App\Repositories\Manufacturer\ManufacturerRepository;
use App\Repositories\Manufacturer\ManufacturerRepositoryInterface;
use App\Repositories\ManufacturerDateLimit\DateLimitRepository;
use App\Repositories\ManufacturerDateLimit\DateLimitRepositoryInterface;
use App\Repositories\Order\Draft\OrderDraftRepository;
use App\Repositories\Order\Draft\OrderDraftRepositoryInterface;
use App\Repositories\Order\OrderFilter;
use App\Repositories\Order\OrderFilterInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Seller\SellerRepository;
use App\Repositories\Seller\SellerRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\AuthUserService;
use App\Services\Auth\AuthUserServiceInterface;
use App\Services\DateLimit\AcceptOrderValidatorService;
use App\Services\DateLimit\AcceptOrderValidatorServiceInterface;
use App\Services\Order\Draft\OrderDraftCreatorService;
use App\Services\Order\Draft\OrderDraftCreatorServiceInterface;
use App\Services\Order\Draft\OrderDraftUpdaterService;
use App\Services\Order\Draft\OrderDraftUpdaterServiceInterface;
use App\Services\Order\Export\OrderExportService;
use App\Services\Order\Export\OrderExportServiceInterface;
use App\Services\Order\OrderCreatorService;
use App\Services\Order\OrderCreatorServiceInterface;
use App\Services\Order\OrderNumberGeneratorService;
use App\Services\Order\OrderNumberGeneratorServiceInterface;
use App\Services\Order\OrderUpdaterService;
use App\Services\Order\OrderUpdaterServiceInterface;
use App\Services\Profile\ProfileService;
use App\Services\Profile\ProfileServiceInterface;
use App\Services\Profile\StyledUserAgentService;
use App\Services\Profile\StyledUserAgentServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        /**
         * REPOSITORIES
         */
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(ManufacturerRepositoryInterface::class, ManufacturerRepository::class);
        $this->app->bind(SellerRepositoryInterface::class, SellerRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(DateLimitRepositoryInterface::class, DateLimitRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderDraftRepositoryInterface::class, OrderDraftRepository::class);

        /**
         * MANAGERS
         */
        $this->app->bind(UserManagerInterface::class, UserManager::class);
        $this->app->bind(RoleManagerInterface::class, RoleManager::class);
        $this->app->bind(UploadManagerInterface::class, UploadManager::class);
        $this->app->bind(ManufacturerManagerInterface::class, ManufacturerManager::class);
        $this->app->bind(SellerManagerInterface::class, SellerManager::class);
        $this->app->bind(DateLimitManagerInterface::class, DateLimitManager::class);
        $this->app->bind(OrderManagerInterface::class, OrderManager::class);
        $this->app->bind(OrderDraftManagerInterface::class, OrderDraftManager::class);

        /**
         * SERVICES
         */
        $this->app->bind(AuthUserServiceInterface::class, AuthUserService::class);
        $this->app->bind(StyledUserAgentServiceInterface::class, StyledUserAgentService::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
        $this->app->bind(ProfileFactoryInterface::class, ProfileFactory::class);
        $this->app->bind(OrderFilterInterface::class, OrderFilter::class);
        $this->app->bind(OrderCreatorServiceInterface::class, OrderCreatorService::class);
        $this->app->bind(OrderUpdaterServiceInterface::class, OrderUpdaterService::class);
        $this->app->bind(OrderDraftCreatorServiceInterface::class, OrderDraftCreatorService::class);
        $this->app->bind(OrderDraftUpdaterServiceInterface::class, OrderDraftUpdaterService::class);
        $this->app->bind(OrderNumberGeneratorServiceInterface::class, OrderNumberGeneratorService::class);
        $this->app->bind(AcceptOrderValidatorServiceInterface::class, AcceptOrderValidatorService::class);
        $this->app->bind(OrderExportServiceInterface::class, OrderExportService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}