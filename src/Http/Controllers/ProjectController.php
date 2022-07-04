<?php

namespace Jeffpereira\RealEstate\Http\Controllers;

use Illuminate\View\View;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jeffpereira\RealEstate\Models\Project\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('jprealestate::projects.list');
    }
    /**
     * View create new resource.
     */
    public function create(Request $request): View
    {
        return view('jprealestate::projects.create_or_edit', ['tab' => $request->tab ?? null]);
    }
    /**
     * View create edit resource.
     */
    public function edit(Request $request, Project $project): View
    {
        return view('jprealestate::projects.create_or_edit', ["propertyId" => $project->id, 'tab' => $request->tab ?? null]);
    }
}
