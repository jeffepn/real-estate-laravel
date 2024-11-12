<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Exception;
use Jeffpereira\RealEstate\Http\Requests\Property\StoreSituationRequest;
use Jeffpereira\RealEstate\Exceptions\CannotDeleteSituationException;
use Jeffpereira\RealEstate\Exceptions\CannotDeleteSituationRelationshipsException;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\Property\UpdateSituationRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\SituationCollection;
use Jeffpereira\RealEstate\Http\Resources\Property\SituationResource;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Jeffpereira\RealEstate\Utilities\Terminologies;
use Symfony\Component\HttpFoundation\Response;

class SituationController extends Controller
{
    public function index()
    {
        $paginate = request()->paginate;
        $situations = Situation::orderBy('name', 'asc');

        if (request()->search) {
            $situations->search(request()->search);
        }

        return new SituationCollection(
            $paginate ? $situations->paginate($paginate) : $situations->get()
        );
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

    public function show(Situation $situation)
    {
        return new SituationResource($situation);
    }

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

    public function destroy(Situation $situation)
    {
        if ($situation->properties()->exists()) {
            throw new CannotDeleteSituationRelationshipsException();
        }

        try {
            $situation->delete();

            return response()->noContent(Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            $this->registerError($th, __METHOD__);

            throw new CannotDeleteSituationException();
        }
    }
}
