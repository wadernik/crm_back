<?php

namespace App\Providers;

use App\Managers\Role\RoleManager;
use App\Managers\Role\RoleManagerInterface;
use App\Managers\User\UserManager;
use App\Managers\User\UserManagerInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
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

        /**
         * MANAGERS
         */
        $this->app->bind(UserManagerInterface::class, UserManager::class);
        $this->app->bind(RoleManagerInterface::class, RoleManager::class);
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