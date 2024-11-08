<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Property;

use Illuminate\Http\Response;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Resources\Property\BusinessCollection;
use Jeffpereira\RealEstate\Http\Requests\Property\BusinessRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\BusinessResource;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class BusinessController extends Controller
{
    public function index()
    {
        try {
            $paginate = request()->paginate;
            $businesses = Business::orderBy('name');

            if (request()->search) {
                $businesses->search(request()->search);
            }

            $businesses->distinct(['businesses.id']);

            return new BusinessCollection(
                $paginate ? $businesses->paginate($paginate) : $businesses->get()
            );
        } catch (\Throwable $th) {
            $this->registerError($th);

            return response([
                'error' => true,
                'message' => Terminologies::get('all.common.error_index'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(BusinessRequest $request)
    {
        try {
            if ($business = Business::create($request->all())) {
                return (new BusinessResource($business, Terminologies::get('all.common.save_data')))
                    ->response()->setStatusCode(Response::HTTP_CREATED);
            }

            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Business $business)
    {
        return new BusinessResource($business);
    }

    public function update(BusinessRequest $request, Business $business)
    {
        try {
            if ($business->update($request->all())) {
                return response(['error' => false, 'message' => Terminologies::get('all.common.save_data')], 200);
            }

            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Business $business)
    {
        try {
            if ($business->delete()) {
                return response()->noContent(Response::HTTP_OK);
            }

            return response(['error' => true, 'message' => Terminologies::get('all.business.not_delete')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            return response(['error' => true, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
