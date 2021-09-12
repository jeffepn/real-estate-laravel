<?php

namespace Jeffpereira\RealEstate;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Observers\Property\ImagePropertyObserver;
use Jeffpereira\RealEstate\Observers\Property\PropertyObserver;

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
        $this->mergeConfigFrom(__DIR__ . '/../config/realestatelaravel.php', 'realestatelaravel');
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
        $this->registerComponents();
        $this->registerCustomRules();
        $this->registerObservers();
    }

    protected function registerPublishes()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/realestatelaravel.php' => config_path('realestatelaravel.php'),
            ], 'realestatelaravel-config');

            $this->publishes([
                __DIR__ . '/../dist/' => public_path('realestatelaravel/'),
            ], 'realestatelaravel-assets');
        }
    }

    protected function loadDependences()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadFactoriesFrom(__DIR__ . "/../database/factories");
        $this->loadViewsFrom(__DIR__ . "/../resources/views", "jprealestate");
    }

    protected function registerCustomRules()
    {
        // Custom rules validation
        Validator::extend('slug', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
        });
    }

    protected function registerObservers()
    {
        Property::observe(PropertyObserver::class);
        ImageProperty::observe(ImagePropertyObserver::class);
    }

    protected function registerComponents()
    {
        Blade::component('jprealestate::components.layout.content', 'content');
    }
}
