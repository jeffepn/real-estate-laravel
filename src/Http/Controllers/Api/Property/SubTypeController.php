<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Exception;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Illuminate\Http\Response;
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
        return new SubTypeCollection(SubType::orderBy('name', 'asc')->get());
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
                    ->response()->setStatusCode(Response::HTTP_CREATED);
            }

            throw new Exception();
        } catch (\Throwable $th) {
            $this->registerError($th, __METHOD__);

            return response(
                [
                    'error' => true,
                    'message' => Terminologies::get('all.resource.error.save'),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
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

            throw new Exception();
        } catch (\Throwable $th) {
            $this->registerError($th, __METHOD__);

            return response(
                [
                    'error' => true,
                    'message' => Terminologies::get('all.resource.error.save'),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
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
                return response(['error' => true, 'message' => Terminologies::get('all.sub_type.not_delete_with_relations')], Response::HTTP_BAD_REQUEST);
            }
            if ($subType->delete()) {
                return response()->noContent(Response::HTTP_OK);
            }

            throw new Exception();
        } catch (\Throwable $th) {
            $this->registerError($th, __METHOD__);

            return response(
                [
                    'error' => true,
                    'message' => Terminologies::get('all.resource.error.delete'),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
