<?php

namespace InnoFlash\LaraStart\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class LaraStartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/larastart.php', 'larastart');
        $this->publishes([
            __DIR__ . '/../config/larastart.php' => config_path('larastart.php')
        ], 'larastart-config');

        Passport::routes();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
