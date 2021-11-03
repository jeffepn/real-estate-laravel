<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Jeffpereira\RealEstate\Http\Requests\Property\StoreSituationRequest;
use Illuminate\Http\Request;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSituationRequest $request)
    {
        try {
            if ($situation = Situation::create($request->all())) {
                return (new SituationResource($situation, Terminologies::get('all.common.save_data')))
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Situation  $situation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Situation $situation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Situation  $situation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Situation $situation)
    {
        //
    }
}
