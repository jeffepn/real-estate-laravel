<?php

namespace JPAddress\Tests;

use Jeffpereira\RealEstate\RealEstateServiceProvider;
// use LaravelLegends\PtBrValidator\ValidatorProvider;
// When testing inside of a Laravel installation, the base class would be Tests\TestCase
class TestCase extends \Orchestra\Testbench\TestCase
{
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
        $this->withFactories(realpath(__DIR__ . "/../src/database/factories"));
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
            'exec'     => 'PRAGMA foreign_keys = ON;'
        ]);

        // $app['config']->set('database.default', 'testbench');
        // $app['config']->set('database.connections.testbench', [
        //     'driver'   => 'mysql',
        //     'host'        => "mysql",
        //     'port'        => '3306',
        //     'database'    => 'test_package_address',
        //     'username'    => "root",
        //     'password'    => "root",
        //     'unix_socket' => "",
        //     'charset'     => 'utf8mb4',
        //     'collation'   => 'utf8mb4_unicode_ci',
        //     'prefix'      => '',
        //     'strict'      => true,
        //     'engine'      => null,
        // ]);
    }
}
