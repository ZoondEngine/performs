<?php

namespace Cryptstick\Performs;

use Illuminate\Support\ServiceProvider;

class PerformsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /**
         * Load default translations
         */
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'performs');

        /**
         * Determine if we are console ?
         */
        if ($this->app->runningInConsole()) {

            /**
             * Publish configs
             */
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('performs.php'),
            ], 'config');

            /**
             * Publish language files
             */
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/performs'),
            ], 'lang');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'performs');

        // Register the main class to use with the facade
        $this->app->singleton('performs', function () {
            return new Performs;
        });
    }
}
