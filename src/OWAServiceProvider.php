<?php

namespace ShahBurhan\OWA;

use Illuminate\Support\ServiceProvider;

class OWAServiceProvider extends ServiceProvider
{
    /**
     * Make config publishment optional by merging the config from the package.
     *
     * @return  void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'owa');
    }
    /**
     * Return default config path
     *
     * @return void
     */
    protected function configPath()
    {
        return __DIR__ . '/../config/owa.php';
    }
}
