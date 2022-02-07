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
    }

    /**
     * Make config publishment optional by merging the config from the package.
     *
     * @return  void
     */
    public function register()
    {
    }
}
