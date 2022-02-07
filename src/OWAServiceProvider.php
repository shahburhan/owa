<?php
namespace ShahBurhan\LaravelPaypal;
use Illuminate\Support\ServiceProvider;

class OWAServiceProvider extends ServiceProvider
{
    /**
    * Publishes configuration file.
    *
    * @return  void
    */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('actions.php'),
        ], 'laravel_owa');
    }

    /**
    * Make config publishment optional by merging the config from the package.
    *
    * @return  void
    */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php',
            'laravel_owa'
        );
    }
}
