<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Jeffpereira\RealEstate\Http\Requests\Property\StoreSituationRequest;
use Illuminate\Http\Response;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\Property\UpdateSituationRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\SituationCollection;
use Jeffpereira\RealEstate\Http\Resources\Property\SituationResource;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class SituationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new SituationCollection(Situation::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSituationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSituationRequest $request)
    {
        try {
            if ($situation = Situation::create($request->all())) {
                return (new SituationResource($situation, Terminologies::get('all.common.save_data')))
                    ->response()->setStatusCode(Response::HTTP_CREATED);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data') . $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Situation  $situation
     * @return \Illuminate\Http\Response
     */
    public function show(Situation $situation)
    {
        return new SituationResource($situation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateSituationRequest  $request
     * @param  \App\Situation  $situation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSituationRequest $request, Situation $situation)
    {
        try {
            if ($situation->update($request->all())) {
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
     * @param  \App\Situation  $situation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Situation $situation)
    {
        try {
            if ($situation->properties->isNotEmpty()) {
                return response(['error' => true, 'message' => Terminologies::get('all.type.not_delete_with_relations')], Response::HTTP_BAD_REQUEST);
            }
            if ($situation->delete()) {
                return response()->noContent(200);
            }
            return response(['error' => true, 'message' => Terminologies::get('all.type.not_delete')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
