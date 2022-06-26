<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\Property\PropertyRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\PropertyCollection;
use Jeffpereira\RealEstate\Http\Resources\Property\PropertyResource;
use Jeffpereira\RealEstate\Models\Property\BusinessProperty;
use Jeffpereira\RealEstate\Utilities\Terminologies;
use Jeffpereira\RealEstate\Http\Requests\Property\StorePropertyRequest;
use Jeffpereira\RealEstate\Http\Requests\Property\UpdatePropertyRequest;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $paginate = request()->paginate;
            $properties = Property::select('properties.*')->orderBy('code', "desc");
            if (request()->search) {
                $properties->search(request()->search);
            }
            $properties->distinct(['properties.id']);

            return new PropertyCollection(
                $paginate ? $properties->paginate($paginate) : $properties->get()
            );
        } catch (\Throwable $th) {
            Log::error("Error index PropertyController", [$th->getTraceAsString()]);
            return response([
                'error' => 'true',
                'message' => Terminologies::get('all.property.error_index')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PropertyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePropertyRequest $request)
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
                ->response()->setStatusCode(Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error("Error store PropertyController", [$th->getTraceAsString()]);
            return response([
                'error' => 'true',
                'message' => Terminologies::get('all.common.error_save_data')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
    public function show(Property $property): JsonResource
    {
        return new PropertyResource($property);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyRequest $request, Property $property): Response
    {
        try {

            if ($request->has('businesses')) {
                $this->updateBusinessesOfProperty($property, $request->all());
            }
            $property->updateAddres($request->all());
            $property->update($request->getData());
            return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')]);
        } catch (\Throwable $th) {
            Log::error("Error update PropertyController", [$th->getTraceAsString()]);
            return response([
                'error' => 'true',
                'message' => Terminologies::get('all.common.error_save_data')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function activeOrInactive(Request $request, Property $property): Response
    {
        try {
            $this->validate($request, ["active" => "required|boolean"]);
            $property->update(['active' => $request->active]);
            return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')]);
        } catch (\Throwable $th) {
            Log::error("Error activeOrInactive PropertyController", [$th->getTraceAsString()]);
            return response([
                'error' => 'true',
                'message' => Terminologies::get('all.property.not_publish_without_dependences')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property): Response
    {
        try {
            return $property->delete()
                ? response()->noContent(Response::HTTP_OK)
                : response(['error' => true, 'message' => Terminologies::get('all.property.not_delete')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            Log::error("Error destroy PropertyController", [$th->getTraceAsString()]);
            return response([
                'error' => true,
                'message' => Terminologies::get('all.property.not_delete')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
