<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Exception;
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
     * @return Response
     */
    public function index()
    {
        return new SituationCollection(Situation::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSituationRequest  $request
     * @return Response
     */
    public function store(StoreSituationRequest $request)
    {
        try {
            if ($situation = Situation::create($request->all())) {
                return (new SituationResource($situation, Terminologies::get('all.common.save_data')))
                    ->response()->setStatusCode(Response::HTTP_CREATED);
            }

            throw new Exception();
        } catch (\Throwable $th) {
            $this->registerError($th, __METHOD__);

            return response(
                [
                    'error' => true,
                    'message' => Terminologies::get('all.common.error_save_data'),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Situation  $situation
     * @return Response
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
     * @return Response
     */
    public function update(UpdateSituationRequest $request, Situation $situation)
    {
        try {
            if ($situation->update($request->all())) {
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
     * @param  \App\Situation  $situation
     * @return Response
     */
    public function destroy(Situation $situation)
    {
        try {
            if ($situation->properties->isNotEmpty()) {
                return response(['error' => true, 'message' => Terminologies::get('all.type.not_delete_with_relations')], Response::HTTP_BAD_REQUEST);
            }
            if ($situation->delete()) {
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
