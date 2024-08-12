<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Exception;
use Illuminate\Http\Response;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\Property\TypeRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\TypeCollection;
use Jeffpereira\RealEstate\Http\Resources\Property\TypeResource;
use Jeffpereira\RealEstate\Models\Property\Type;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $paginate = request()->paginate;
        $types = Type::orderBy('name', 'asc');

        if (request()->search) {
            $types->search(request()->search);
        }

        return new TypeCollection(
            $paginate ? $types->paginate($paginate) : $types->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TypeRequest $request
     * @return Response
     */
    public function store(TypeRequest $request)
    {
        try {
            if ($type = Type::create($request->all())) {
                return (new TypeResource($type, Terminologies::get('all.common.save_data')))
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
     * @param  \App\Models\Type  $type
     * @return Response
     */
    public function show(Type $type)
    {
        return new TypeResource($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TypeRequest $request
     * @param  \App\Models\Type  $type
     * @return Response
     */
    public function update(TypeRequest $request, Type $type)
    {
        try {
            if ($type->update($request->all())) {
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
     * @param  \App\Models\Type  $type
     * @return Response
     */
    public function destroy(Type $type)
    {
        try {
            if ($type->sub_types->count() > 0) {
                return response(['error' => true, 'message' => Terminologies::get('all.type.not_delete_with_relations')], Response::HTTP_BAD_REQUEST);
            }
            if ($type->delete()) {
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
