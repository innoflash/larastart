<?php

namespace InnoFlash\LaraStart\Providers;

use Illuminate\Support\ServiceProvider;
use InnoFlash\LaraStart\Console\Commands\MakeServiceCommand;

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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/larastart.php' => config_path('larastart.php')
            ], 'larastart-config');

            $this->commands([
                MakeServiceCommand::class
            ]);
        }
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
