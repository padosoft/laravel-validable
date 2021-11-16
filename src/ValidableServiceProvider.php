<?php


namespace Padosoft\Laravel\Validable;

class ValidableServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                             __DIR__ . '/config/config.php' => config_path('padosoft-notification.php')
                         ], 'config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->mergeConfigFrom(
            __DIR__ . '/config/config.php',
            'laravel-validable'
        );
    }
}
