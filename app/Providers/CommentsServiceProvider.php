<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class CommentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('comments.php'),
            ], 'config');

            if (! class_exists('CreateCommentsTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_comments_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_comments_table.php'),
                ], 'migrations');
            }
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'comments');
    }
}