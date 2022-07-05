<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Person;

use Exception;
use Illuminate\Database\QueryException;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Jeffpereira\RealEstate\Http\Requests\Person\StoreTypePersonRequest;
use Jeffpereira\RealEstate\Http\Requests\Person\UpdateTypePersonRequest;
use Jeffpereira\RealEstate\Http\Resources\Person\TypePersonCollection;
use Jeffpereira\RealEstate\Http\Resources\Person\TypePersonResource;
use Jeffpereira\RealEstate\Models\Person\TypePerson;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class TypePersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $paginate = request()->paginate;
            $type_people = TypePerson::select('type_people.*')->orderBy('name');

            if (request()->search) {
                $type_people->search(request()->search);
            }

            $type_people->distinct(['type_people.id']);

            if (request()->with) {
                $type_people->with(explode(',', request()->with));
            }

            return new TypePersonCollection(
                $paginate ? $type_people->paginate($paginate) : $type_people->get()
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
     * @param  StoreTypePersonRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypePersonRequest $request)
    {
        try {
            $typePerson = TypePerson::create($request->all());

            if (!$typePerson) {
                throw new Exception();
            }

            return new TypePersonResource(
                $typePerson,
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
     * @param  TypePerson  $typePerson
     * @return \Illuminate\Http\Response
     */
    public function show(TypePerson $typePerson)
    {
        return new TypePersonResource($typePerson);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateTypePersonRequest  $request
     * @param  TypePerson  $typePerson
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTypePersonRequest $request, TypePerson $typePerson)
    {
        try {
            $typePerson->update($request->all());

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
     * @param  TypePerson  $typePerson
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypePerson $typePerson)
    {
        try {
            if (!$typePerson->delete()) {
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
