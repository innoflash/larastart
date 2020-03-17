<?php

namespace InnoFlash\LaraStart\Providers;

use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use InnoFlash\LaraStart\Mixins\ResponseMixin;
use InnoFlash\LaraStart\Services\AuthService;

class LaraStartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function boot()
    {
        ResponseFactory::mixin(new ResponseMixin());

        $this->mergeConfigFrom(__DIR__ . '/../../config/larastart.php', 'larastart');

        $this->publishes([
            __DIR__ . '/../../config/larastart.php' => config_path('larastart.php')
        ], 'larastart-config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AuthService::class);
    }
}
