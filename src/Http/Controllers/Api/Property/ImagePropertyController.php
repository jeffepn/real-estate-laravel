<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\Property\ImagePropertyRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\ImagePropertyResource;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;
use Jeffpereira\RealEstate\Utilities\Terminologies;
use Jeffpereira\RealEstate\Http\Controllers\Traits\TreatmentImages;
use Jeffpereira\RealEstate\Http\Requests\Property\ImagePropertyUpdateOrderRequest;
use Jeffpereira\RealEstate\Http\Requests\Property\ImagePropertyUpdateRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\ImagePropertyCollection;
use Jeffpereira\RealEstate\Services\DownloadService;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use TypeError;

class ImagePropertyController extends Controller
{
    use TreatmentImages;

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
            $altImage = $property->generateAltImage();
            $imagesStore = collect();
            foreach ($request->images as $image) {
                $ways = $this->storageImage(
                    'properties',
                    $image,
                    $altImage,
                    $request->use_watter_mark,
                    $request->watermark_position
                );
                $imagesStore->push(
                    ImageProperty::create([
                        'property_id' => $property->id,
                        'way' => $ways['way'],
                        'thumbnail' => $ways['thumbnail'],
                        'alt' => $altImage,
                    ])
                );
            }

            return (new ImagePropertyCollection($imagesStore))
                ->additional([
                    'error' => false,
                    'message' => Terminologies::get('all.common.save_data'),
                ])
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (ModelNotFoundException $mn) {
            logger()->error('Error in store ImagePropertyController: ' . $mn->getMessage(), [
                'exception' => $mn,
            ]);

            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (Throwable $th) {
            $this->registerError($th, __METHOD__);

            return response()->noContent(Response::HTTP_INTERNAL_SERVER_ERROR);
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
    public function update(ImagePropertyUpdateRequest $request, ImageProperty $imageProperty)
    {
        $data = [];
        $wayOld = $imageProperty->way;
        $thumbnailOld = $imageProperty->thumbnail;
        if ($request->has('image')) {
            $property = Property::findOrFail($imageProperty->property_id);
            $altImage = $property->generateAltImage();
            $ways = $this->storageImage('properties', $request->image, $altImage);
            $data['alt'] = $altImage;
            $data = array_merge($data, $ways);
        }

        $updated = $imageProperty->update($data);

        if ($updated) {
            Storage::disk(ConfigHelper::get('filesystem.disk'))
                ->delete($wayOld);
            if ($thumbnailOld) {
                Storage::disk(ConfigHelper::get('filesystem.disk'))
                    ->delete($thumbnailOld);
            }
        }

        return response()->noContent(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImageProperty $imageProperty): Response
    {
        try {
            return $imageProperty->delete()
                ? response()->noContent(Response::HTTP_OK)
                : response([
                    'error' => true,
                    'message' => Terminologies::get('all.property.not_delete'),
                ], Response::HTTP_BAD_REQUEST);
        } catch (Throwable $th) {
            logger()->error('Error in destroy ImagePropertyController: ' . $th->getMessage(), [
                'exception' => $th,
            ]);

            return response([
                'error' => true,
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function bulkDestroy(Request $request): Response
    {
        $imageProperties = ImageProperty::whereIn('id', $request->ids)->get();
        $ids = [];
        foreach ($imageProperties as $imageProperty) {
            try {
                $imageProperty->delete();
                array_push($ids, $imageProperty->id);
            } catch (Throwable $th) {
            }
        }

        return response([
            'error' => false,
            'message' => Terminologies::get('all.resource.delete'),
            'ids' => $ids,
        ], Response::HTTP_OK);
    }

    public function updateOrder(ImagePropertyUpdateOrderRequest $request): Response
    {
        foreach ($request->orders as $key => $contentOrder) {
            $image = ImageProperty::findOrFail($contentOrder['id']);
            $image->update(['order' => $contentOrder['order']]);
        }

        return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')], 200);
    }

    public function bulkDownload(Request $request, DownloadService $downloadService)
    {
        try {
            $files = ImageProperty::whereIn('id', $request->ids)->get();

            return response()->download(
                $downloadService->createZipDownload(
                    $files->map(function ($file) {
                        return $file->urlStorage();
                    })->toArray()
                )
            );
        } catch (TypeError $err) {
            $this->registerError(new Exception($err->getMessage(), $err->getCode()));

            return redirect()->back();
        } catch (Throwable $th) {
            $this->registerError($th);

            return redirect()->back();
        }
    }
}
