<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Illuminate\Http\Request;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Resources\Property\SituationCollection;
use Jeffpereira\RealEstate\Models\Property\Situation;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Situation  $situation
     * @return \Illuminate\Http\Response
     */
    public function show(Situation $situation)
    {
        //
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
