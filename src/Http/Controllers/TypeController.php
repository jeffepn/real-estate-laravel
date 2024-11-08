<?php

namespace Jeffpereira\RealEstate\Http\Controllers;

use Illuminate\View\View;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('jprealestate::types.list');
    }
}
