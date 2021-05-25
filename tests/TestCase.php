<?php

namespace Jeffpereira\RealEstate\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Facades\Config;
use Jeffpereira\RealEstate\RealEstateServiceProvider;
// use LaravelLegends\PtBrValidator\ValidatorProvider;
// When testing inside of a Laravel installation, the base class would be Tests\TestCase
class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $loadEnvironmentVariables = true;

    // When testing inside of a Laravel installation, this is not needed
    protected function getPackageProviders($app)
    {
        return [
            RealEstateServiceProvider::class, //ValidatorProvider::class
        ];
    }
    // When testing inside of a Laravel installation, this is not needed
    protected function setUp(): void
    {
        parent::setUp();
        // $this->withFactories(realpath(__DIR__ . "/../src/database/factories"));
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/jeffersonpereira/address/src/migrations');

        $this->artisan('config:clear')->run();
        $this->artisan('migrate', ['--database ' => 'testbench'])->run();
    }
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->useEnvironmentPath(__DIR__ . '/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'mysql',
            'host'        => env('DB_HOST', 'localhost'),
            'port'        => env('DB_PORT', 3306),
            'database'    => env('DB_DATABASE', 'database'),
            'username'    => env('DB_USERNAME', 'forge'),
            'password'    => env('DB_PASSWORD', 'forge'),
            'unix_socket' => "",
            'charset'     => 'utf8mb4',
            'collation'   => 'utf8mb4_unicode_ci',
            'prefix'      => '',
            'strict'      => true,
            'engine'      => env('DB_ENGINE', null),
        ]);
        //dd(config('database'));
        parent::getEnvironmentSetUp($app);
        // Setup default database to use sqlite :memory:
        // $app['config']->set('database.default', 'testbench');
        // $app['config']->set('database.connections.testbench', [
        //     'driver'   => 'sqlite',
        //     'database' => ':memory:',
        //     'prefix'   => '',
        //     'exec'     => 'PRAGMA foreign_keys = ON;'
        // ]);

    }
}
