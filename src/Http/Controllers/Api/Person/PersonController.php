<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Person;

use Exception;
use Illuminate\Database\QueryException;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Jeffpereira\RealEstate\Http\Requests\Person\StorePersonRequest;
use Jeffpereira\RealEstate\Http\Requests\Person\UpdatePersonRequest;
use Jeffpereira\RealEstate\Http\Resources\Person\PersonCollection;
use Jeffpereira\RealEstate\Http\Resources\Person\PersonResource;
use Jeffpereira\RealEstate\Models\Person\Person;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $paginate = request()->paginate;
            $people = Person::select('people.*')->orderBy('name');

            if (request()->search) {
                $people->search(request()->search);
            }

            $people->distinct(['people.id']);

            if (request()->with) {
                $people->with(explode(',', request()->with));
            }

            return new PersonCollection(
                $paginate ? $people->paginate($paginate) : $people->get()
            );
        } catch (\Throwable $th) {
            $this->registerError($th);

            return response([
                'error' => true,
                'message' => Terminologies::get('all.resource.error.get'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePersonRequest  $request
     * @return Response
     */
    public function store(StorePersonRequest $request)
    {
        try {
            $person = Person::create($request->all());

            if (!$person) {
                throw new Exception();
            }

            $person->loadMissing('type');

            return new PersonResource(
                $person,
                Terminologies::get('all.resource.success.save'),
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            $this->registerError($th);

            return response([
                'error' => true,
                'message' => Terminologies::get('all.resource.error.save'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Person  $person
     * @return Response
     */
    public function show(Person $person)
    {
        if (request()->with) {
            $person->loadMissing(explode(',', request()->with));
        }

        return new PersonResource($person);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePersonRequest  $request
     * @param  Person  $person
     * @return Response
     */
    public function update(UpdatePersonRequest $request, Person $person)
    {
        try {
            $person->update($request->all());

            return response([
                'error' => false,
                'message' => Terminologies::get('all.resource.success.save'),
            ]);
        } catch (\Throwable $th) {
            $this->registerError($th);

            return response([
                'error' => true,
                'message' => Terminologies::get('all.resource.error.save'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Person  $person
     * @return Response
     */
    public function destroy(Person $person)
    {
        try {
            if (!$person->delete()) {
                throw new Exception();
            }

            return response([
                'error' => false,
                'message' => Terminologies::get('all.resource.success.delete'),
            ]);
        } catch (\Throwable $th) {
            $this->registerError($th);
            $message = $th instanceof QueryException
                ? 'all.resource.error.delete_relationships'
                : 'all.resource.error.delete';

            return response([
                'error' => true,
                'message' => Terminologies::get($message),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
