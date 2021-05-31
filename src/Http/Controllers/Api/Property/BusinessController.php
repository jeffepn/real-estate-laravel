<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Resources\Property\BusinessCollection;
use Jeffpereira\RealEstate\Http\Requests\Property\BusinessRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\BusinessResource;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new BusinessCollection(Business::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BusinessRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BusinessRequest $request)
    {
        try {
            if ($business = Business::create($request->all())) {
                return response(['id' => $business->id], 201);
            }
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        } catch (\Throwable $th) {
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Property\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        return new BusinessResource($business);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BusinessRequest  $request
     * @param  \App\Models\Property\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(BusinessRequest $request, Business $business)
    {
        try {
            if ($business->update($request->all())) {
                return response()->noContent(200);
            }
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        } catch (\Throwable $th) {
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Property\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        try {
            if ($business->properties->count() > 0) {
                return response(['error' => true, 'message' => Terminologies::get('all.business.not_delete_with_relations')], 400);
            }
            if ($business->delete()) {
                return response()->noContent(200);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.business.not_delete')], 400);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], 400);
        }
    }
}
