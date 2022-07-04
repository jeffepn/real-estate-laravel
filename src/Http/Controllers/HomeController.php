<?php

namespace Jeffpereira\RealEstate\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display view dashboar.
     */
    public function dashboard(): View
    {
        return view('jprealestate::dashboard');
    }
}
