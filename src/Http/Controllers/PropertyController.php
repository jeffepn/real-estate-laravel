<?php

namespace Jeffpereira\RealEstate\Http\Controllers;

use Illuminate\Http\Request;
use Jeffpereira\RealEstate\Models\Property\Property;
use Illuminate\View\View;
use Jeffpereira\RealEstate\Http\Controllers\Controller;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(): View
    {
        return view('jpviews::properties.list');
    }
    /**
     * View create new resource.
     */
    public function create(Request $request): View
    {
        return view('jpviews::properties.create_or_edit', ['tab' => $request->tab ?? null]);
    }
    /**
     * View create edit resource.
     */
    public function edit(Request $request, Property $property): View
    {
        return view('jpviews::properties.create_or_edit', ["propertyId" => $property->id, 'tab' => $request->tab ?? null]);
    }
}
