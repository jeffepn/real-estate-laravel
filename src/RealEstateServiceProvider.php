<?php

namespace Jeffpereira\RealEstate;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class RealEstateServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Jeffpereira\RealEstate\Console\Commands\Install'
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadDependences();
        $this->registerPublishes();
        $this->registerCustomRules();
    }

    protected function registerPublishes()
    {
        $this->publishes([
            __DIR__ . '/../config/realestatelaravel.php' => config_path('realestatelaravel.php'),
        ], 'realestatelaravel-config');

        $this->publishes([
            __DIR__ . '/../dist/' => public_path('assets/'),
        ], 'realestatelaravel-assets');
    }

    protected function loadDependences()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadFactoriesFrom(__DIR__ . "/../database/factories");
        $this->loadViewsFrom(__DIR__ . "/../resources/views", "jpviews");
    }

    protected function registerCustomRules()
    {
        // Custom rules validation
        Validator::extend('slug', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
        });
    }
}
