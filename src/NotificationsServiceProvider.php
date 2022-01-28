<?php

namespace Khonik\Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishMigrations();
        $this->publishViews();
        include __DIR__ . '/routes.php';
    }

    private function publishMigrations()
    {
        $path = $this->getMigrationsPath();
        $this->publishes([$path => database_path('migrations')], 'migrations');
    }

    private function publishViews()
    {
        $path = $this->getViewsPath();
        $this->publishes([$path => resource_path('views')], 'views');
    }

    private function getViewsPath(): string
    {
        return __DIR__ . '/views/';
    }

    private function getMigrationsPath(): string
    {
        return __DIR__ . '/migrations/';
    }
}
