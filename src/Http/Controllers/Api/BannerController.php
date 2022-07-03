<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api;

use Illuminate\Http\Response;
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
            $data = $request->only(['title', 'content', 'link']);
            $data['way'] = Storage::disk(config('realestatelaravel.filesystem.disk'))
                ->put(
                    config('realestatelaravel.filesystem.path.banners'),
                    $request->image
                );

            if ($banner = Banner::create($data)) {
                return (new BannerResource($banner->refresh(), Terminologies::get('all.common.save_data')))
                    ->response()->setStatusCode(Response::HTTP_CREATED);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
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
    public function update(BannerRequest $request, Banner $banner)
    {
        try {
            $data = $request->only(['title', 'content', 'link']);
            if ($request->image) {
                $data['way'] = Storage::disk(config('realestatelaravel.filesystem.disk'))
                    ->put(config('realestatelaravel.filesystem.path.banners'), $request->image);
            }
            if ($banner->update($data)) {
                return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')], 200);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
        }
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
                return response()->noContent(Response::HTTP_OK);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.property.not_delete')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
