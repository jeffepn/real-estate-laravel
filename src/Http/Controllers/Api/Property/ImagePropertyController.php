<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Jeffpereira\RealEstate\Models\Property\Property;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\Property\ImagePropertyRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\ImagePropertyResource;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;
use Jeffpereira\RealEstate\Utilities\Terminologies;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Http\Requests\Property\ImagePropertyUpdateOrderRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\ImagePropertyCollection;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class ImagePropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResource
    {
        $property = Property::findOrFail($request->property_id);
        return new ImagePropertyCollection($property->images);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImagePropertyRequest $request)
    {
        try {
            $property = Property::findOrFail($request->property_id);
            $altImage =  $property->generateAltImage();
            if (config('realestatelaravel.filesystem.entities.properties.optmize')) {
                ImageOptimizer::optimize($request->file('image')->getRealPath());
            }
            $resultUpload = Storage::disk(config('realestatelaravel.filesystem.entities.properties.disk'))
                ->putFileAs(
                    config('realestatelaravel.filesystem.entities.properties.path'),
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
            logger('Error in store ImagePropertyController: ' . $mn->getMessage(), $mn->getTrace());
            return response()->noContent(400);
        } catch (\Throwable $th) {
            logger('Error in store ImagePropertyController: ' . $th->getMessage(), $th->getTrace());
            return response()->noContent(500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ImageProperty $imageProperty): JsonResource
    {
        return new ImagePropertyResource($imageProperty);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ImagePropertyRequest $request, Property $imageProperty)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImageProperty $imageProperty): Response
    {
        try {

            return $imageProperty->delete()
                ? response()->noContent(200)
                : response([
                    'error' => true,
                    'message' => Terminologies::get('all.property.not_delete')
                ], 400);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateOrder(ImagePropertyUpdateOrderRequest $request): Response
    {
        foreach ($request->orders as $key => $contentOrder) {
            $image = ImageProperty::findOrFail($contentOrder['id']);
            $image->update(['order' => $contentOrder['order']]);
        }
        return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')], 200);
    }
}
