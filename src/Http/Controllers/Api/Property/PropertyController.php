<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Jeffpereira\RealEstate\Models\Property\Property;
use Illuminate\Http\Request;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\Property\PropertyRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\PropertyCollection;
use Jeffpereira\RealEstate\Http\Resources\Property\PropertyResource;
use Jeffpereira\RealEstate\Utilities\Terminologies;
use JPAddress\Models\Address\Address;
use JPAddress\Models\Address\City;
use JPAddress\Models\Address\Country;
use JPAddress\Models\Address\Neighborhood;
use JPAddress\Models\Address\State;
use PHPUnit\Framework\Constraint\Count;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new PropertyCollection(Property::orderBy('code', "desc")->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PropertyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PropertyRequest $request)
    {
        try {
            $address = $this->generateAddress($request->getDataAddress());

            $data = $request->getData();
            $data['address_id'] = $address->id;
            if ($property = Property::create($data)) {
                return (new PropertyResource($property->refresh(), Terminologies::get('all.common.save_data')))
                    ->response()->setStatusCode(201);
            }
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        } catch (\Throwable $th) {
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property)
    {
        return new PropertyResource($property);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PropertyRequest  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function update(PropertyRequest $request, Property $property)
    {
        try {
            $property->address->update($request->getDataAddress());
            if ($property->update($request->getData())) {
                return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')], 200);
            }
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data') . 'kkkkk'], 400);
        } catch (\Throwable $th) {
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data') . $th->getMessage() . $th->getFile() . $th->getLine()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property)
    {
        try {
            if ($property->delete()) {
                return response()->noContent(200);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.property.not_delete')], 400);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], 400);
        }
    }

    private function generateAddress(array $dataAddress): Address
    {
        $dataAddress['neighborhood_id'] = Neighborhood::firstOrCreate([
            'name' => $dataAddress['neighborhood'],
            'city_id' => City::firstOrCreate([
                "name" => $dataAddress['city'],
                'state_id' => State::firstOrCreate([
                    'name' => $dataAddress['state'],
                    'initials' => $dataAddress['initials'],
                    'country_id' => Country::firstOrCreate(['name' => $dataAddress['country']])->id
                ])->id
            ])->id
        ])->id;
        return Address::create($dataAddress);
    }
}
