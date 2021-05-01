<?php

namespace Rushable\GooglePlace;

use Illuminate\Support\ServiceProvider;
use Rushable\GooglePlace\Contract\GooglePlace;
use Rushable\GooglePlace\Service\GooglePlaceService;

class GoolePlaceServiceProvider extends ServiceProvider
{
    private const CONFIG_PATH = '/config/googleplace.php';

    public function boot(): void
    {
        $this->publishes([
            dirname(__DIR__) . self::CONFIG_PATH => $this->app->basePath(self::CONFIG_PATH)
        ]);

    }

    public function register(): void
    {
        $this->app->singleton(GooglePlace::class, function ($app) {
            return new GooglePlaceService(config('googleplace.key'));
        });
    }
}
