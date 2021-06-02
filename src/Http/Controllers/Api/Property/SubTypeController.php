<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jeffpereira\RealEstate\Http\Requests\Property\SubTypeRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\SubTypeCollection;
use Jeffpereira\RealEstate\Http\Resources\Property\SubTypeResource;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class SubTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new SubTypeCollection(SubType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubTypeRequest $request)
    {
        try {
            if ($subType = SubType::create($request->all())) {
                return (new SubTypeResource($subType, Terminologies::get('all.common.save_data')))
                    ->response()->setStatusCode(201);
            }
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        } catch (\Throwable $th) {
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data') . $th->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubType  $subType
     * @return \Illuminate\Http\Response
     */
    public function show(SubType $subType)
    {
        return new SubTypeResource($subType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SubTypeRequest  $request
     * @param  \App\Models\SubType  $subType
     * @return \Illuminate\Http\Response
     */
    public function update(SubTypeRequest $request, SubType $subType)
    {
        try {
            if ($subType->update($request->all())) {
                return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')], 200);
            }
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        } catch (\Throwable $th) {
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data')], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubType  $subType
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubType $subType)
    {
        try {
            if ($subType->properties->count() > 0) {
                return response(['error' => true, 'message' => Terminologies::get('all.sub_type.not_delete_with_relations')], 400);
            }
            if ($subType->delete()) {
                return response()->noContent(200);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.sub_type.not_delete')], 400);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], 400);
        }
    }
}
