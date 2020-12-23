<?php

namespace Simplepay\SimplepayApi;

use Illuminate\Support\ServiceProvider;

class SimplepayApiServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'simplepay');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'simplepay');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/simplepay-api.php', 'simplepay-api');

        // Register the service the package provides.
        $this->app->singleton('simplepay-api', function ($app) {
            return new SimplepayApi;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['simplepay-api'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/simplepay-api.php' => config_path('simplepay-api.php'),
        ], 'simplepay-api.config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/simplepay'),
        ], 'simplepay-api.views');

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/simplepay'),
        ], 'simplepay-api.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/simplepay'),
        ], 'simplepay-api.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
