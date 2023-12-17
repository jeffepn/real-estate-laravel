<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api\Project;

use Exception;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Jeffpereira\RealEstate\Http\Requests\Project\StoreProjectRequest;
use Jeffpereira\RealEstate\Http\Requests\Project\UpdateProjectRequest;
use Jeffpereira\RealEstate\Http\Resources\Project\ProjectCollection;
use Jeffpereira\RealEstate\Http\Resources\Project\ProjectResource;
use Jeffpereira\RealEstate\Models\Project\Project;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            $paginate = request()->paginate;
            $projects = Project::select('projects.*')->orderBy('name');

            if (request()->search) {
                $projects->search(request()->search);
            }
            $projects->distinct(['projects.id']);

            if (request()->with) {
                $projects->with(explode(',', request()->with));
            }

            return new ProjectCollection(
                $paginate ? $projects->paginate($paginate) : $projects->get()
            );
        } catch (\Throwable $th) {
            $this->registerError($th);

            return response([
                'error' => true,
                'message' => Terminologies::get('all.common.error_index'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreProjectRequest $request)
    {
        try {
            $project = Project::create($request->all());

            if (!$project) {
                throw new Exception();
            }

            $project->loadMissing('responsible');

            return new ProjectResource(
                $project,
                Terminologies::get('all.common.save_data'),
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            $this->registerError($th);

            return response([
                'error' => true,
                'message' => Terminologies::get('all.common.error_save_data'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Project $project)
    {
        if (request()->with) {
            $project->loadMissing(explode(',', request()->with));
        }

        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        try {
            $project->update($request->all());

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

    public function destroy(Project $project)
    {
        try {
            if (!$project->delete()) {
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
