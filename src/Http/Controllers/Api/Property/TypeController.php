<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new TypeCollection(Type::orderBy("name", "asc")->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TypeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeRequest $request)
    {
        try {
            if ($type = Type::create($request->all())) {
                return (new TypeResource($type, Terminologies::get('all.common.save_data')))
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
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(TypeRequest $request, Type $type)
    {
        try {
            if ($type->update($request->all())) {
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
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
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
            return response(['error' => true, 'message' => Terminologies::get('all.type.not_delete')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
