<?php

namespace Jeffpereira\RealEstate\Tests;

use Faker\Provider\pt_BR\Address as Pt_BRAddress;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Testing\WithFaker;
use Jeffpereira\RealEstate\RealEstateServiceProvider;
use JPAddress\JPAddressServiceProvider;
use JPAddress\Models\Address\Address;
use JPAddress\Models\Address\City;
use JPAddress\Models\Address\Country;
use JPAddress\Models\Address\Neighborhood;
use JPAddress\Models\Address\State;
use LaravelLegends\PtBrValidator\ValidatorProvider;

// use LaravelLegends\PtBrValidator\ValidatorProvider;
// When testing inside of a Laravel installation, the base class would be Tests\TestCase
class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithFaker;

    public function createAddress()
    {
        return Address::factory()->create([
            'cep' => '99999999',
            'neighborhood_id' => Neighborhood::factory()->create([
                'city_id' => City::factory()->create([
                    'state_id' => State::factory()->create([
                        'country_id' => Country::firstOrCreate(['name' => 'brasil'])->id,
                    ]),
                ])->id,
            ])->id,
        ]);
    }
    //protected $loadEnvironmentVariables = true;

    // When testing inside of a Laravel installation, this is not needed
    protected function getPackageProviders($app)
    {
        return [
            RealEstateServiceProvider::class, JPAddressServiceProvider::class, ValidatorProvider::class,
        ];
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
        $app['config']->set('realestatelaravel', config('realestatelaravel'));
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', 'database'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', 'forge'),
            'unix_socket' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => env('DB_ENGINE', null),
        ]);
        parent::getEnvironmentSetUp($app);
    }

    // When testing inside of a Laravel installation, this is not needed
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('config:clear')->run();
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        $this->faker->addProvider(new Pt_BRAddress($this->faker));
    }
}
