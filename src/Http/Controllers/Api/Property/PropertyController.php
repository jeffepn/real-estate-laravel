<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
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
            $address = $this->generateAddress($request->getDataAddress());

            $data = $request->getData();
            $data['address_id'] = $address->id;
            if ($property = Property::create($data)) {
                if ($request->has('businesses')) {
                    $this->updateBusinessesOfProperty($property, $request->getDataBusinesses());
                }
                return (new PropertyResource($property->refresh(), Terminologies::get('all.common.save_data')))
                    ->response()->setStatusCode(201);
            }
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        } catch (\Throwable $th) {
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        }
    }

    private function updateBusinessesOfProperty(Property $property, array $businesses)
    {
        $businesses = collect($businesses);
        $businesses->map(function ($business) use ($property) {
            BusinessProperty::updateOrCreate(
                ['property_id' => $property->id, 'business_id' => $business['id']],
                ['value' => $business['value']]
            );
        });
        $property->businessesProperty->map(function ($businessProperty) use ($businesses) {
            if ($businesses->filter(function ($business) use ($businessProperty) {
                return $business['id'] === $businessProperty->business_id;
            })->count() < 1) {
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
            if (!$this->checkPublish($request, $property)) {
                return response([
                    'error' => 'true',
                    'message' => Terminologies::get('all.property.not_publish_without_dependences')
                ], 400);
            }
            if ($request->has('businesses')) {
                $this->updateBusinessesOfProperty($property, $request->getDataBusinesses());
            }
            $property->address->update($request->getDataAddress());
            if (!$this->checkPublish($request, $property)) {
                return response([
                    'error' => 'true',
                    'message' => Terminologies::get('all.property.not_publish_without_dependences')
                ], 400);
            }
            if ($property->update($request->getData())) {
                return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')], 200);
            }
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        } catch (\Throwable $th) {
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data') . $th->getMessage()], 400);
        }
    }

    public function checkPublish($request, $property)
    {
        if (!$request->active) {
            return true;
        }
        $haveBusinessesProperty = ($property->businessesProperty->count() > 0);
        $haveImages = ($property->images->count() > 0);
        $haveEmbed = ($request->has("embed") ? $request->embed : $property->embed);
        $haveMedia = ($haveImages || $haveEmbed);
        return $haveBusinessesProperty && $haveMedia ? true : false;
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

    public function indexImage(Property $property)
    {
        return new ImagePropertyCollection($property->images);
    }

    public function uploadImage(ImagePropertyRequest $request)
    {
        try {
            $property = Property::findOrFail($request->property_id);
            $altImage =  $property->generateAltImage();
            if (config('realestatelaravel.optmize_images')) {
                ImageOptimizer::optimize($request->file('image')->getRealPath());
            }
            $resultUpload = Storage::disk(config('realestatelaravel.filesystem.disk'))
                ->putFileAs(
                    config('realestatelaravel.filesystem.path.properties'),
                    $request->image,
                    Str::slug($altImage) .  Str::uuid() . '.' . $request->image->extension()
                );
            $image = ImageProperty::create([
                'property_id' => $property->id,
                'way' => $resultUpload,
                'alt' => $altImage
            ]);
            return (new ImagePropertyResource($image, Terminologies::get('all.common.save_data')))
                ->response()->setStatusCode(201);
        } catch (ModelNotFoundException $mn) {
            return response()->noContent(400);
        }
    }

    public function updateOrder(ImagePropertyUpdateOrderRequest $request)
    {
        foreach ($request->orders as $key => $contentOrder) {
            $image = ImageProperty::findOrFail($contentOrder['id']);
            $image->update(['order' => $contentOrder['order']]);
        }
        return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')], 200);
    }

    public function destroyImage(ImageProperty $imageProperty)
    {
        try {
            $way = $imageProperty->way;
            if ($imageProperty->delete()) {
                Storage::disk(config('realestatelaravel.filesystem.disk'))->delete($way);
                return response()->noContent(200);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.property.not_delete')], 400);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], 400);
        }
    }
}
