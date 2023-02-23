<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Project;

use Exception;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Jeffpereira\RealEstate\Http\Controllers\Traits\TreatmentImages;
use Jeffpereira\RealEstate\Http\Requests\Project\StoreImageProjectRequest;
use Jeffpereira\RealEstate\Http\Resources\Project\ImageProjectCollection;
use Jeffpereira\RealEstate\Models\Common\Image;
use Jeffpereira\RealEstate\Models\Project\ImageProject;
use Jeffpereira\RealEstate\Models\Project\Project;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class ImageProjectController extends Controller
{
    use TreatmentImages;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        request()->validate(['project_id' => 'required|exists:projects,id']);

        try {
            $project = Project::findOrFail(request()->project_id);

            return new ImageProjectCollection($project->imagesProject);
        } catch (\Throwable $th) {
            $this->registerError($th);

            return response([
                'error' => true,
                'message' => Terminologies::get('all.resource.error.get'),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreImageProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageProjectRequest $request)
    {
        try {
            $project = Project::findOrFail($request->project_id);
            $imagesStore = collect();
            foreach ($request->images as $dataImage) {
                $image = $dataImage['image'];
                $altImage = Arr::get($dataImage, 'alt') ?? $project->generateAltImage();
                $ways = $this->storageImage('projects', $image, $altImage, $request->use_watter_mark);
                $image = Image::create(
                    array_merge(
                        [
                            'way' => $ways['way'],
                            'thumbnail' => $ways['thumbnail'],
                            'alt' => $altImage,
                        ],
                        $request->only(['title', 'description', 'author'])
                    )
                );
                $imagesStore->push(
                    ImageProject::create([
                        'project_id' => $project->id,
                        'image_id' => $image->id,
                    ])
                );
            }

            return new ImageProjectCollection(
                $imagesStore,
                Terminologies::get('all.resource.success.save'),
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            $this->registerError($th);

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
     * @param  ImageProject  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageProject $imageProject)
    {
        try {
            if (!$imageProject->delete()) {
                throw new Exception();
            }

            return response([
                'error' => false,
                'message' => Terminologies::get('all.resource.success.delete'),
            ]);
        } catch (\Throwable $th) {
            $this->registerError($th);

            return response([
                'error' => true,
                'message' => Terminologies::get('all.resource.error.delete'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
