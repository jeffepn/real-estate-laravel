<?php

namespace Jeffpereira\RealEstate;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Jeffpereira\RealEstate\Models\AppSettings;
use Jeffpereira\RealEstate\Models\Property\BusinessProperty;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Observers\AppSettingsObserver;
use Jeffpereira\RealEstate\Observers\Property\BusinessPropertyObserver;
use Jeffpereira\RealEstate\Observers\Property\ImagePropertyObserver;
use Jeffpereira\RealEstate\Observers\Property\PropertyObserver;

class RealEstateServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Jeffpereira\RealEstate\Console\Commands\Install',
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
        // Register the service provider for the dependency.
        $this->app->register('Intervention\Image\ImageServiceProvider');
        $this->app->register(EventServiceProvider::class);
        // Create aliases for the dependency.
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Image', 'Intervention\Image\Facades\Image');
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
        $this->registerBladeDirectives();
    }

    protected function registerPublishes()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/realestatelaravel.php' => config_path('realestatelaravel.php'),
            ], 'realestatelaravel-config');

            // Disable in version 1.3.19 to use in cdn
            // $this->publishes([
            //     __DIR__ . '/../dist/' => public_path('realestatelaravel/'),
            // ], 'realestatelaravel-assets');
        }
    }

    protected function loadDependences()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'jprealestate');
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
        AppSettings::observe(AppSettingsObserver::class);
        BusinessProperty::observe(BusinessPropertyObserver::class);
    }

    protected function registerComponents()
    {
        Blade::component('jprealestate::components.layout.content', 'content');
    }

    // Create protected function to register blade directives
    protected function registerBladeDirectives()
    {
        Blade::directive('realestatelaravelStyles', [RealEstateBladeDirective::class, 'mainStyles']);
        Blade::directive('realestatelaravelScripts', [RealEstateBladeDirective::class, 'mainScripts']);
    }
}
