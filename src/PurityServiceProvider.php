<?php

namespace Abbasudo\Purity;

use Illuminate\Support\ServiceProvider;

class PurityServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/purity.php', 'purity');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/purity.php' => config_path('purity.php'),
            ], 'config');
        }
    }
}
