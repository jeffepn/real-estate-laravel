<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jeffpereira\RealEstate\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\BannerRequest;
use Jeffpereira\RealEstate\Http\Resources\BannerCollection;
use Jeffpereira\RealEstate\Http\Resources\BannerResource;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new BannerCollection(Banner::orderBy('created_at', "desc")->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BannerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        try {
            $address = $this->generateAddress($request->getDataAddress());

            $data = $request->getData();
            $data['address_id'] = $address->id;
            if ($property = Banner::create($data)) {
                if ($request->has('businesses')) {
                    $this->updateBusinessesOfBanner($property, $request->getDataBusinesses());
                }
                return (new BannerResource($property->refresh(), Terminologies::get('all.common.save_data')))
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
     * @param  \App\Models\Banner  $property
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $property)
    {
        return new BannerResource($property);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BannerRequest  $request
     * @param  \App\Models\Banner  $property
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request, Banner $property)
    {
        try {
            if (!$this->checkPublish($request, $property)) {
                return response([
                    'error' => 'true',
                    'message' => Terminologies::get('all.property.not_publish_without_dependences')
                ], 400);
            }
            if ($request->has('businesses')) {
                $this->updateBusinessesOfBanner($property, $request->getDataBusinesses());
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
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data') . $th->getMessage() . $th->getFile() . $th->getLine()], 400);
        }
    }

    public function checkPublish($request, $property)
    {
        if (!$request->active) {
            return true;
        }
        $haveBusinessesBanner = ($property->businessesBanner->count() > 0);
        $haveImages = ($property->images->count() > 0);
        $haveEmbed = ($request->has("embed") ? $request->embed : $property->embed);
        $haveMedia = ($haveImages || $haveEmbed);
        return $haveBusinessesBanner && $haveMedia ? true : false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $property
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        try {
            if ($banner->delete()) {
                return response()->noContent(200);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.property.not_delete')], 400);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], 400);
        }
    }

    /*private function generateAddress(array $dataAddress): Address
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

    public function indexImage(Banner $property)
    {
        return new ImageBannerCollection($property->images);
    }

    public function uploadImage(ImageBannerRequest $request)
    {
        try {
            $property = Banner::findOrFail($request->property_id);
            $altImage =  $property->generateAltImage();
            $resultUpload = Storage::disk(config('realestatelaravel.filesystem.disk'))
                ->putFileAs(
                    config('realestatelaravel.filesystem.path.properties'),
                    $request->image,
                    Str::slug($altImage) .  Str::uuid() . '.' . $request->image->extension()
                );
            $image = ImageBanner::create([
                'property_id' => $property->id,
                'way' => $resultUpload,
                'alt' => $altImage
            ]);
            return (new ImageBannerResource($image, Terminologies::get('all.common.save_data')))
                ->response()->setStatusCode(201);
        } catch (ModelNotFoundException $mn) {
            return response()->noContent(400);
        }
    }

    public function destroyImage(ImageBanner $imageBanner)
    {
        try {
            $way = $imageBanner->way;
            if ($imageBanner->delete()) {
                Storage::disk(config('realestatelaravel.filesystem.disk'))->delete($way);
                return response()->noContent(200);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.property.not_delete')], 400);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], 400);
        }
    }*/
}
