<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Exception;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Jeffpereira\RealEstate\Http\Requests\Property\SubTypeRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\SubTypeCollection;
use Jeffpereira\RealEstate\Http\Resources\Property\SubTypeResource;
use Jeffpereira\RealEstate\Models\Property\SubType;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class SubTypeController extends Controller
{
    public function index()
    {
        $paginate = request()->paginate;
        $subTypes = SubType::orderBy('name', 'asc');

        if (request()->search) {
            $subTypes->search(request()->search);
        }

        return new SubTypeCollection(
            $paginate ? $subTypes->paginate($paginate) : $subTypes->get()
        );
    }

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

    public function show(SubType $subType)
    {
        return new SubTypeResource($subType);
    }

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
