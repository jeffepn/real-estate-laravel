<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Jeffpereira\RealEstate\Models\Property\Property;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\Property\ImagePropertyRequest;
use Jeffpereira\RealEstate\Http\Requests\Property\PropertyRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\ImagePropertyResource;
use Jeffpereira\RealEstate\Http\Resources\Property\PropertyCollection;
use Jeffpereira\RealEstate\Http\Resources\Property\PropertyResource;
use Jeffpereira\RealEstate\Models\Property\BusinessProperty;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;
use Jeffpereira\RealEstate\Utilities\Terminologies;
use JPAddress\Models\Address\Address;
use JPAddress\Models\Address\City;
use JPAddress\Models\Address\Country;
use JPAddress\Models\Address\Neighborhood;
use JPAddress\Models\Address\State;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Http\Requests\Property\ImagePropertyUpdateOrderRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\ImagePropertyCollection;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

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
            $address = Property::createAddress($request->all());

            $data = $request->getData();
            $data['address_id'] = $address->id;
            $property = Property::create($data);
            if ($request->has('businesses')) {
                $this->updateBusinessesOfProperty($property, $request->all());
            }
            return (new PropertyResource($property->refresh(), Terminologies::get('all.common.save_data')))
                ->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            logger("Error store PropertyController: " . $th->getMessage(), $th->getTrace());
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 500);
        }
    }

    private function updateBusinessesOfProperty(Property $property, array $data)
    {
        if (!Arr::has($data, 'businesses')) {
            return;
        }

        $businesses = collect($data['businesses']);
        $businesses->each(function ($business) use ($property) {
            BusinessProperty::updateOrCreate(
                ['property_id' => $property->id, 'business_id' => $business['id']],
                ['value' => $business['value']]
            );
        });

        $property->businessesProperty
            ->each(function ($businessProperty) use ($businesses) {
                $business = $businessProperty
                    ->business()
                    ->whereNotIn('id', $businesses->pluck('id'))
                    ->first();
                if ($business) {
                    $businessProperty->delete();
                }
            });
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

            if ($request->has('businesses')) {
                $this->updateBusinessesOfProperty($property, $request->all());
            }
            $property->updateAddres($request->all());
            $property->update($request->getData());
            return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')], 200);
        } catch (\Throwable $th) {
            logger("Error update PropertyController: " . $th->getMessage(), $th->getTrace());
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data') . $th->getMessage()], 500);
        }
    }

    public function activeOrInactive(Request $request, Property $property)
    {
        try {
            $this->validate($request, ["active" => "required|boolean"]);
            $property->update(['active' => $request->active]);
            return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')], 200);
        } catch (\Throwable $th) {
            logger("Error activeOrInactive PropertyController: " . $th->getMessage(), $th->getTrace());
            return response([
                'error' => 'true',
                'message' => Terminologies::get('all.property.not_publish_without_dependences')
            ], 500);
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
            return $property->delete()
                ? response()->noContent(200)
                : response(['error' => true, 'message' => Terminologies::get('all.property.not_delete')], 400);
        } catch (\Throwable $th) {
            logger("Error destroy PropertyController: " . $th->getMessage(), $th->getTrace());
            return response(['error' => true, 'message' => $th->getMessage()], 500);
        }
    }
}
