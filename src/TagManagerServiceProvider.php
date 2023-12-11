<?php

namespace Label84\TagManager;

use Illuminate\Support\ServiceProvider;
use Label84\TagManager\View\Components\Body;
use Label84\TagManager\View\Components\Head;
use Label84\TagManager\View\Components\MeasurementProtocolClientId;

class TagManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'tagmanager');

        $this->app->singleton(TagManager::class, function ($app) {
            return new TagManager();
        });

        $this->app->singleton(MeasurementProtocol::class, function ($app) {
            return new MeasurementProtocol();
        });

        $this->app->alias(TagManager::class, 'tagmanager');
        $this->app->alias(MeasurementProtocol::class, 'measurement_protocol');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
              __DIR__.'/../config/config.php' => config_path('tagmanager.php'),
            ], 'config');
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tagmanager');

        $this->loadViewComponentsAs('tagmanager', [
            Head::class,
            Body::class,
            MeasurementProtocolClientId::class,
        ]);
    }
}
