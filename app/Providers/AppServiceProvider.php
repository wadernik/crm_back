<?php

namespace App\Providers;

use App\Managers\Manufacturer\ManufacturerManager;
use App\Managers\Manufacturer\ManufacturerManagerInterface;
use App\Managers\ManufacturerDateLimit\DateLimitManager;
use App\Managers\ManufacturerDateLimit\DateLimitManagerInterface;
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
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Seller\SellerRepository;
use App\Repositories\Seller\SellerRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
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

        /**
         * MANAGERS
         */
        $this->app->bind(UserManagerInterface::class, UserManager::class);
        $this->app->bind(RoleManagerInterface::class, RoleManager::class);
        $this->app->bind(UploadManagerInterface::class, UploadManager::class);
        $this->app->bind(ManufacturerManagerInterface::class, ManufacturerManager::class);
        $this->app->bind(SellerManagerInterface::class, SellerManager::class);
        $this->app->bind(DateLimitManagerInterface::class, DateLimitManager::class);
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