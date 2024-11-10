<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Resources\Property\PropertyCollection;
use Jeffpereira\RealEstate\Http\Resources\Property\PropertyResource;
use Jeffpereira\RealEstate\Models\Property\BusinessProperty;
use Jeffpereira\RealEstate\Utilities\Terminologies;
use Jeffpereira\RealEstate\Http\Requests\Property\StorePropertyRequest;
use Jeffpereira\RealEstate\Http\Requests\Property\UpdatePropertyRequest;

class PropertyController extends Controller
{
    public function index()
    {
        try {
            $paginate = request()->paginate;
            $properties = Property::select('properties.*')->orderBy('code', 'desc');
            if (request()->search) {
                $properties->search(request()->search);
            }
            $properties->distinct(['properties.id']);

            return new PropertyCollection(
                $paginate ? $properties->paginate($paginate) : $properties->get()
            );
        } catch (\Throwable $th) {
            Log::error('Error index PropertyController', [
                'exception' => $th,
            ]);

            return response([
                'error' => true,
                'message' => Terminologies::get('all.property.error_index'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StorePropertyRequest $request): JsonResponse
    {
        try {
            $address = Property::createAddress($request->all());

            $data = $request->getData();
            $data['address_id'] = $address->id;
            $property = Property::create($data);
            if ($request->has('businesses')) {
                $this->updateBusinessesOfProperty($property, $request->input('businesses', []));
            }

            return (new PropertyResource($property->refresh(), Terminologies::get('all.common.save_data')))
                ->response()->setStatusCode(Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Error store PropertyController', [
                'exception' => $th,
            ]);

            return response()
                ->json(
                    ['error' => true, 'message' => Terminologies::get('all.common.error_save_data')],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
        }
    }

    public function show(Property $property): JsonResource
    {
        return new PropertyResource($property);
    }

    public function update(UpdatePropertyRequest $request, Property $property): JsonResponse
    {
        try {
            if ($request->has('businesses')) {
                $this->updateBusinessesOfProperty($property, $request->input('businesses', []));
            }
            $property->updateAddres($request->all());
            $property->update($request->getData());

            return response()
                ->json(['error' => false, 'message' => Terminologies::get('all.common.save_data')]);
        } catch (\Throwable $th) {
            Log::error('Error update PropertyController', [
                'exception' => $th,
            ]);

            return response()->json(
                ['error' => true, 'message' => Terminologies::get('all.common.error_save_data')],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function activeOrInactive(Request $request, Property $property): JsonResponse
    {
        try {
            $this->validate($request, ['active' => 'required|boolean']);
            $property->update(['active' => $request->active]);

            return response()
                ->json(['error' => false, 'message' => Terminologies::get('all.common.save_data')]);
        } catch (\Throwable $th) {
            Log::error('Error activeOrInactive PropertyController', [
                'exception' => $th,
            ]);

            return response()
                ->json(
                    ['error' => true, 'message' => Terminologies::get('all.property.not_publish_without_dependences')],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
        }
    }

    public function destroy(Property $property): JsonResponse
    {
        try {
            return $property->delete()
                ? response()->json()
                : response()->json(
                    ['error' => true, 'message' => Terminologies::get('all.property.not_delete')],
                    Response::HTTP_BAD_REQUEST
                );
        } catch (\Throwable $th) {
            Log::error('Error destroy PropertyController', [
                'exception' => $th,
            ]);

            return response()
                ->json(
                    ['error' => true, 'message' => Terminologies::get('all.property.not_delete')],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
        }
    }

    /**
     * Update or create BusinessProperty records for a given Property and array of businesses.
     *
     * Delete BusinessProperty records for businesses not present in the $businesses array
     *
     * @param Property $property The Property model instance.
     * @param array $businesses An array of businesses with 'id' and 'value' keys.
     * @return void
     */
    private function updateBusinessesOfProperty(Property $property, array $businesses)
    {
        if (empty($businesses)) {
            return;
        }

        foreach ($businesses as $business) {
            logger('JEFF', Arr::only($business, ['value', 'old_value', 'status_situation']));
            BusinessProperty::updateOrCreate(
                ['property_id' => $property->id, 'business_id' => $business['id']],
                Arr::only($business, ['value', 'old_value', 'status_situation'])
            );
        }

        $property->businessesProperty()
            ->whereNotIn('business_id', Arr::pluck($businesses, 'id'))
            ->delete();
    }
}
