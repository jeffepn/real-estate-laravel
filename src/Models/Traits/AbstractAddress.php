<?php

namespace Jeffpereira\RealEstate\Models\Traits;

use Illuminate\Support\Arr;
use JPAddress\Models\Address\Address;
use JPAddress\Models\Address\City;
use JPAddress\Models\Address\Country;
use JPAddress\Models\Address\Neighborhood;
use JPAddress\Models\Address\State;

trait AbstractAddress
{
    static $columnsAddress = ["address", "number", "not_number", "complement", "cep", "longitude", "latitude"];
    static $columnsCreateUpdateNeighborhood  = ["neighborhood", "city", "state", "initials", "country"];

    abstract protected function getInstanceAddress(): Address;

    public static function createAddress(array $data): Address
    {
        $sanitiseData = Arr::only($data, self::$columnsAddress);
        $neighborhood = self::createNeighborhood(Arr::only($data, self::$columnsCreateUpdateNeighborhood));
        $sanitiseData['neighborhood_id'] = $neighborhood->id;
        return Address::create($sanitiseData);
    }

    public function updateAddres(array $data): Address
    {
        $address = $this->getInstanceAddress();
        $sanitiseData = Arr::only($data, self::$columnsAddress);
        $neighborhood = $this->updateNeighborhood(Arr::only($data, self::$columnsCreateUpdateNeighborhood));
        $sanitiseData['neighborhood_id'] = $neighborhood->id;
        $address->update($sanitiseData);
        return $address;
    }


    private function updateNeighborhood(array $data): Neighborhood
    {
        $address = $this->getInstanceAddress();
        $country = Arr::has($data, ['country'])
            ? Country::firstOrCreate(['name' => $data['country']])
            : $address->neighborhood->city->state->country;
        $state = Arr::has($data, ['state', 'initials'])
            ? State::firstOrCreate([
                'name' => $data['state'], 'initials' => $data['initials'], 'country_id' => $country->id
            ])
            : $address->neighborhood->city->state;
        $city = Arr::has($data, ['city'])
            ? City::firstOrCreate([
                'name' => $data['city'], 'state_id' => $state->id
            ])
            : $address->neighborhood->city;
        $neighborhood = Arr::has($data, ['neighborhood'])
            ? Neighborhood::firstOrCreate([
                'name' => $data['neighborhood'], 'city_id' => $city->id
            ])
            : $address->neighborhood;
        return $neighborhood;
    }

    private static function createNeighborhood(array $data): Neighborhood
    {
        return Neighborhood::firstOrCreate([
            'name' => $data['neighborhood'],
            'city_id' => City::firstOrCreate([
                "name" => $data['city'],
                'state_id' => State::firstOrCreate([
                    'name' => $data['state'],
                    'initials' => $data['initials'],
                    'country_id' => Country::firstOrCreate(['name' => $data['country']])->id
                ])->id
            ])->id
        ]);
    }
}
