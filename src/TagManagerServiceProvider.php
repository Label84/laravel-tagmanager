<?php

namespace Label84\TagManager;

use Illuminate\Support\ServiceProvider;
use Label84\TagManager\View\Components\Body;
use Label84\TagManager\View\Components\Head;

class TagManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'tagmanager');

        $this->app->singleton(TagManager::class, function ($app) {
            return new TagManager();
        });

        $this->app->alias(TagManager::class, 'tagmanager');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
              __DIR__.'/../config/config.php' => config_path('tagmanager.php'),
            ], 'config');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tagmanager');

        $this->loadViewComponentsAs('tagmanager', [
            Head::class,
            Body::class,
        ]);
    }
}
