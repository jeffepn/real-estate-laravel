<?php

namespace Jeffpereira\RealEstate\Http\Resources\Property;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Jeffpereira\RealEstate\Http\Resources\BusinessPropertyResource;
use JPAddress\Resources\AddressResource;
use JPAddress\Resources\CityResource;
use JPAddress\Resources\CountryResource;
use JPAddress\Resources\NeighborhoodResource;
use JPAddress\Resources\StateResource;

class PropertyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($property) {
            return new PropertyResource($property);
        });
    }

    public function with($request)
    {
        $includes = [];
        $businessesProperty = $this->collection->pluck('businessesProperty')->unique()->flatten()->values()->map(function ($businessProperty) {
            return new BusinessPropertyResource($businessProperty);
        })->toArray();
        $businesses = $this->collection->pluck('businessesProperty')->unique()->flatten()->values()->map(function ($businessProperty) {
            return new BusinessResource($businessProperty->business);
        })->toArray();
        $types = $this->collection->pluck('sub_type.type')->unique()->values()->map(function ($type) {
            return new TypeResource($type);
        })->toArray();
        $subTypes = $this->collection->pluck('sub_type')->unique()->values()->map(function ($subType) {
            return new SubTypeResource($subType);
        })->toArray();
        $addresses = $this->collection->pluck('address')->unique()->values()->map(function ($address) {
            return new AddressResource($address);
        })->toArray();
        $neighborhoods = $this->collection->pluck('address.neighborhood')->unique()->values()->map(function ($neighborhood) {
            return new NeighborhoodResource($neighborhood);
        })->toArray();
        $cities = $this->collection->pluck('address.neighborhood.city')->unique()->values()->map(function ($city) {
            return new CityResource($city);
        })->toArray();
        $states = $this->collection->pluck('address.neighborhood.city.state')->unique()->values()->map(function ($state) {
            return new StateResource($state);
        })->toArray();
        $countries = $this->collection->pluck('address.neighborhood.city.state.country')->unique()->values()->map(function ($country) {
            return new CountryResource($country);
        })->toArray();
        $includes = array_merge($includes, $businessesProperty);
        $includes = array_merge($includes, $businesses);
        $includes = array_merge($includes, $types);
        $includes = array_merge($includes, $subTypes);
        $includes = array_merge($includes, $addresses);
        $includes = array_merge($includes, $neighborhoods);
        $includes = array_merge($includes, $cities);
        $includes = array_merge($includes, $states);
        $includes = array_merge($includes, $countries);

        return [
            'included' => $includes,
        ];
    }
}
