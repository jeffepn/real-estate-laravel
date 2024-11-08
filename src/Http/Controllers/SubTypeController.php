<?php

namespace Jeffpereira\RealEstate\Http\Controllers;

use Illuminate\View\View;

class SubTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('jprealestate::sub_types.list');
    }
}
