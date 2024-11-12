<?php

namespace Jeffpereira\RealEstate\Http\Controllers;

use Illuminate\View\View;

class SituationController extends Controller
{
    public function index(): View
    {
        return view('jprealestate::situations.list');
    }
}
