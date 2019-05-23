<?php

namespace App\Providers;

use App\Components\TreeBuilder\TreeBuilder;
use App\Components\TreeBuilder\TreeBuilderInterface;
use App\Components\Storages\RedisCacheStorage;
use App\Components\Storages\StorageInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(StorageInterface::class, RedisCacheStorage::class);
        $this->app->bind(TreeBuilderInterface::class, TreeBuilder::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
